<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatronDetail;
use App\Models\User;
use App\Models\Role;
use App\Models\PatronAddress;
use App\Models\ActivityLog;
use App\Models\PatronGroup;
use App\Services\BarcodeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PatronsImport;
use Carbon\Carbon;

class PatronImportController extends Controller
{
    protected $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    /**
     * Display import form
     */
    public function index()
    {
        return view('admin.patrons.import');
    }

    /**
     * Download template
     */
    public function template()
    {
        $filePath = public_path('templates/patron_import_template_36_rows.xlsx');
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File template không tồn tại. Vui lòng liên hệ admin.');
        }
        
        return response()->download($filePath, 'patron_import_template_36_rows.xlsx');
    }

    /**
     * Upload and process Excel file
     */
    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('excel_file');
            $filePath = $file->store('imports', 'local');

            // Process Excel and get data
            $import = new PatronsImport();
            $import->import($filePath);

            if ($import->failures()) {
                // Log detailed failures for debugging
                $failureDetails = [];
                $failures = $import->failures();
                
                // Handle both array and collection
                if (is_array($failures)) {
                    foreach ($failures as $failure) {
                        $failureDetails[] = "Row " . $failure['row'] . ": " . implode(', ', (array)$failure['errors']);
                    }
                } else {
                    foreach ($failures as $failure) {
                        $failureDetails[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
                    }
                }
                
                \Log::error('Import validation failures:', $failureDetails);
                
                return back()
                    ->with('failures', $failures)
                    ->with('error_details', $failureDetails);
            }

            // Get imported data for preview
            $data = $import->getData();
            
            // Log data count for debugging
            \Log::info('Import data count: ' . count($data));
            if (!empty($data)) {
                \Log::info('First row data:', $data[0]);
            }
            
            return redirect()->route('admin.patrons.import.preview')
                ->with('import_data', $data)
                ->with('file_path', $filePath);

        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage());
            \Log::error('Import trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Lỗi xử lý file: ' . $e->getMessage());
        }
    }

    /**
     * Show preview and column mapping
     */
    public function preview()
    {
        $data = session('import_data');
        $filePath = session('file_path');

        if (!$data || !$filePath) {
            return redirect()->route('admin.patrons.import.index');
        }

        // Get available columns from first row
        $columns = array_keys($data[0] ?? []);
        
        // Auto-map Vietnamese columns to database fields
        $autoMapping = $this->getAutoColumnMapping($columns);
        
        // Get show parameter from request (default to show all)
        $showAll = request()->get('show_all', true);
        $showCount = $showAll ? count($data) : min(10, count($data));
        $previewData = array_slice($data, 0, $showCount);
        
        return view('admin.patrons.import-preview', compact('data', 'previewData', 'columns', 'filePath', 'autoMapping', 'showAll'));
    }

    /**
     * Auto-map Vietnamese column names to database fields
     */
    private function getAutoColumnMapping($columns)
    {
        $mapping = [];
        
        foreach ($columns as $column) {
            $lowerColumn = strtolower($column);
            
            // Map Vietnamese column names to database fields
            if (str_contains($lowerColumn, 'mã') || str_contains($lowerColumn, 'ma') || str_contains($lowerColumn, 'code')) {
                $mapping[$column] = 'patron_code';
            }
            elseif (str_contains($lowerColumn, 'họ') || str_contains($lowerColumn, 'ho') || str_contains($lowerColumn, 'tên') || str_contains($lowerColumn, 'ten')) {
                if (str_contains($lowerColumn, 'hiển thị') || str_contains($lowerColumn, 'hien_thi')) {
                    $mapping[$column] = 'display_name';
                } else {
                    $mapping[$column] = 'name';
                }
            }
            elseif (str_contains($lowerColumn, 'email')) {
                $mapping[$column] = 'email';
            }
            elseif (str_contains($lowerColumn, 'điện thoại') || str_contains($lowerColumn, 'dien_thoai') || str_contains($lowerColumn, 'phone')) {
                $mapping[$column] = 'phone';
            }
            elseif (str_contains($lowerColumn, 'mssv') || str_contains($lowerColumn, 'student')) {
                $mapping[$column] = 'mssv';
            }
            elseif (str_contains($lowerColumn, 'cmnd') || str_contains($lowerColumn, 'cccd') || str_contains($lowerColumn, 'id_card')) {
                $mapping[$column] = 'id_card';
            }
            elseif (str_contains($lowerColumn, 'trường') || str_contains($lowerColumn, 'truong') || str_contains($lowerColumn, 'school')) {
                $mapping[$column] = 'school_name';
            }
            elseif (str_contains($lowerColumn, 'bộ phận') || str_contains($lowerColumn, 'bo_phan') || str_contains($lowerColumn, 'lớp') || str_contains($lowerColumn, 'lop') || str_contains($lowerColumn, 'department')) {
                $mapping[$column] = 'department';
            }
            elseif (str_contains($lowerColumn, 'khóa') || str_contains($lowerColumn, 'khoa') || str_contains($lowerColumn, 'batch')) {
                $mapping[$column] = 'batch';
            }
            elseif (str_contains($lowerColumn, 'sinh') || str_contains($lowerColumn, 'ngày sinh') || str_contains($lowerColumn, 'dob')) {
                $mapping[$column] = 'dob';
            }
            elseif (str_contains($lowerColumn, 'giới tính') || str_contains($lowerColumn, 'gioi_tinh') || str_contains($lowerColumn, 'gender')) {
                $mapping[$column] = 'gender';
            }
            elseif (str_contains($lowerColumn, 'địa chỉ') || str_contains($lowerColumn, 'dia_chi') || str_contains($lowerColumn, 'address')) {
                $mapping[$column] = 'address';
            }
            elseif (str_contains($lowerColumn, 'ghi chú') || str_contains($lowerColumn, 'ghi_chu') || str_contains($lowerColumn, 'notes')) {
                $mapping[$column] = 'notes';
            }
        }
        
        return $mapping;
    }

    /**
     * Process column mapping and import
     */
    public function process(Request $request)
    {
        $request->validate([
            'file_path' => 'required',
            'column_mapping' => 'required|array',
            'expiry_date' => 'nullable|date',
        ]);

        try {
            $filePath = $request->input('file_path');
            $columnMapping = $request->input('column_mapping');
            $expiryDate = $request->input('expiry_date', Carbon::now()->addYear()->format('Y-m-d'));

            \Log::info('Starting import process:', [
                'file_path' => $filePath,
                'column_mapping' => $columnMapping,
                'expiry_date' => $expiryDate
            ]);

            // Re-read Excel with mapping
            $import = new PatronsImport();
            $import->setColumnMapping($columnMapping);
            $import->setExpiryDate($expiryDate);
            
            \Log::info('Import object created, starting import...');
            
            // Import data to get rows
            $import->import($filePath);
            
            \Log::info('Import completed, data count: ' . count($import->getData()));
            
            // Process actual import
            $importedCount = $import->processImport();
            
            \Log::info('ProcessImport completed, imported count: ' . $importedCount);
            
            // Clean up temp file
            if (file_exists(storage_path('app/' . $filePath))) {
                unlink(storage_path('app/' . $filePath));
            }

            return redirect()->route('admin.patrons.index')
                ->with('success', "Đã import thành công {$importedCount} bạn đọc!");

        } catch (\Exception $e) {
            \Log::error('Import process error: ' . $e->getMessage());
            \Log::error('Import process trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Lỗi import: ' . $e->getMessage());
        }
    }

    /**
     * Handle image upload for batch import
     */
    public function uploadImages(Request $request)
    {
        $request->validate([
            'images_zip' => 'required|mimes:zip|max:51200', // 50MB max
        ]);

        try {
            $file = $request->file('images_zip');
            $zipPath = $file->store('imports/images', 'local');
            $extractPath = storage_path('app/imports/images/extracted_' . time());

            // Extract ZIP
            $zip = new \ZipArchive();
            if ($zip->open(storage_path('app/' . $zipPath)) === TRUE) {
                $zip->extractTo($extractPath);
                $zip->close();
            }

            // Get image files
            $images = [];
            $files = glob($extractPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            
            foreach ($files as $file) {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                $images[$filename] = $file;
            }

            session(['import_images' => $images]);

            return response()->json([
                'success' => true,
                'count' => count($images),
                'images' => array_keys($images)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create template file
     */
    private function createTemplateFile($path)
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = [
            'patron_code' => 'Mã bạn đọc',
            'name' => 'Họ và tên',
            'display_name' => 'Tên hiển thị',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'mssv' => 'MSSV',
            'id_card' => 'Số CMND/CCCD',
            'school_name' => 'Trường',
            'department' => 'Bộ phận/Lớp',
            'batch' => 'Khóa',
            'dob' => 'Ngày sinh',
            'gender' => 'Giới tính',
            'address' => 'Địa chỉ',
            'notes' => 'Ghi chú'
        ];

        $col = 'A';
        foreach ($headers as $key => $label) {
            $sheet->setCellValue($col . '1', $label);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle($col . '1')->getFill()->getStartColor()->setRGB('E3F2FD');
            $col++;
        }

        // Sample data - 36 rows (mix of teachers and students)
        $sampleData = [
            // Teachers (Giáo Viên) - Group ID 2
            ['GV001', 'Nguyễn Văn An', 'PGS.TS Nguyễn Văn An', 'nguyenvana@vttu.edu.vn', '0901234567', '', '123456789012', 'Đại học Văn Lang', 'Khoa Công nghệ thông tin', '', '1975-05-15', 'Nam', '123 Nguyễn Huệ, Q1, TP.HCM', 'Trưởng khoa CNTT'],
            ['GV002', 'Trần Thị Bình', 'TS Trần Thị Bình', 'tranthib@vttu.edu.vn', '0912345678', '', '234567890123', 'Đại học Văn Lang', 'Khoa Kinh doanh', '', '1980-03-20', 'Nữ', '456 Lê Lợi, Q3, TP.HCM', 'Phó khoa Kinh doanh'],
            ['GV003', 'Lê Văn Cường', 'ThS. Lê Văn Cường', 'levanc@vttu.edu.vn', '0923456789', '', '345678901234', 'Đại học Văn Lang', 'Khoa Thiết kế', '', '1978-07-10', 'Nam', '789 Đồng Khởi, Q1, TP.HCM', 'Giảng viên Thiết kế đồ họa'],
            ['GV004', 'Phạm Thị Dung', 'PGS.TS Phạm Thị Dung', 'phamthid@vttu.edu.vn', '0934567890', '', '456789012345', 'Đại học Văn Lang', 'Khoa Ngôn ngữ', '', '1982-11-25', 'Nữ', '321 Võ Văn Tần, Q3, TP.HCM', 'Trưởng khoa Ngôn ngữ Anh'],
            ['GV005', 'Hoàng Văn Em', 'TS Hoàng Văn Em', 'hoangvane@vttu.edu.vn', '0945678901', '', '567890123456', 'Đại học Văn Lang', 'Khoa Luật', '', '1976-09-08', 'Nam', '654 Cách Mạng Tháng 8, Q3, TP.HCM', 'Giảng viên Luật'],
            ['GV006', 'Đỗ Thị Phương', 'ThS. Đỗ Thị Phương', 'dothiphuong@vttu.edu.vn', '0956789012', '', '678901234567', 'Đại học Văn Lang', 'Khoa Kiến trúc', '', '1985-12-12', 'Nữ', '987 Trần Hưng Đạo, Q5, TP.HCM', 'Giảng viên Kiến trúc'],
            ['GV007', 'Vũ Văn Giang', 'PGS.TS Vũ Văn Giang', 'vuvang@vttu.edu.vn', '0967890123', '', '789012345678', 'Đại học Văn Lang', 'Khoa Du lịch', '', '1979-04-18', 'Nam', '147 Nguyễn Trãi, Q1, TP.HCM', 'Trưởng khoa Du lịch'],
            ['GV008', 'Ngô Thị Hà', 'TS Ngô Thị Hà', 'ngothih@vttu.edu.vn', '0978901234', '', '890123456789', 'Đại học Văn Lang', 'Khoa Tài chính', '', '1983-06-30', 'Nữ', '258 Bà Triệu, Q1, TP.HCM', 'Phó khoa Tài chính'],
            ['GV009', 'Đinh Văn Ích', 'ThS. Đinh Văn Ích', 'dinhvani@vttu.edu.vn', '0989012345', '', '901234567890', 'Đại học Văn Lang', 'Khoa Marketing', '', '1981-02-14', 'Nam', '369 Nguyễn Thị Minh Khai, Q1, TP.HCM', 'Giảng viên Marketing'],
            ['GV010', 'Bùi Thị Khanh', 'PGS.TS Bùi Thị Khanh', 'buithikh@vttu.edu.vn', '0990123456', '', '012345678901', 'Đại học Văn Lang', 'Khoa Quốc tế học', '', '1977-08-22', 'Nữ', '741 Hai Bà Trưng, Q1, TP.HCM', 'Trưởng khoa Quốc tế học'],
            ['GV011', 'Trương Văn Lực', 'TS Trương Văn Lực', 'truongvanluc@vttu.edu.vn', '0902345678', '', '123045678901', 'Đại học Văn Lang', 'Khoa Công nghệ thông tin', '', '1984-01-05', 'Nam', '852 Trường Chinh, Q.Tân Bình, TP.HCM', 'Giảng viên CNTT'],
            ['GV012', 'Lê Thị Mai', 'ThS. Lê Thị Mai', 'lethimai@vttu.edu.vn', '0913456789', '', '234156789012', 'Đại học Văn Lang', 'Khoa Kinh doanh', '', '1986-07-28', 'Nữ', '963 Lý Thường Kiệt, Q.10, TP.HCM', 'Giảng viên Kinh doanh'],
            
            // Students (Học Sinh) - Group ID 3
            ['ST001', 'Nguyễn Văn Khoa', 'Nguyễn Văn Khoa', 'nguyenkhoa@vttu.edu.vn', '0909876543', '2021001', '123456789012', 'Đại học Văn Lang', 'Công nghệ thông tin', 'K45', '2003-01-15', 'Nam', '123 Nguyễn Huệ, Q1, TP.HCM', 'Sinh viên năm 4 CNTT'],
            ['ST002', 'Trần Thị Linh', 'Trần Thị Linh', 'tranlinh@vttu.edu.vn', '0918765432', '2021002', '234567890123', 'Đại học Văn Lang', 'Quản trị kinh doanh', 'K45', '2003-03-20', 'Nữ', '456 Lê Lợi, Q3, TP.HCM', 'Sinh viên năm 4 QTDL'],
            ['ST003', 'Lê Minh Quân', 'Lê Minh Quân', 'leminhquan@vttu.edu.vn', '0927654321', '2020001', '345678901234', 'Đại học Văn Lang', 'Kiến trúc', 'K44', '2002-07-10', 'Nam', '789 Đồng Khởi, Q1, TP.HCM', 'Sinh viên năm 5 Kiến trúc'],
            ['ST004', 'Phạm Thu An', 'Phạm Thu An', 'phamthuan@vttu.edu.vn', '0936543210', '2022001', '456789012345', 'Đại học Văn Lang', 'Thiết kế đồ họa', 'K46', '2004-11-25', 'Nữ', '321 Võ Văn Tần, Q3, TP.HCM', 'Sinh viên năm 2 THDH'],
            ['ST005', 'Hoàng Đức Bảo', 'Hoàng Đức Bảo', 'hoangducbao@vttu.edu.vn', '0945432109', '2021003', '567890123456', 'Đại học Văn Lang', 'Ngôn ngữ Anh', 'K45', '2003-09-08', 'Nam', '654 Cách Mạng Tháng 8, Q3, TP.HCM', 'Sinh viên năm 4 NNA'],
            ['ST006', 'Đỗ Thảo Vy', 'Đỗ Thảo Vy', 'dothaovy@vttu.edu.vn', '0954321098', '2020002', '678901234567', 'Đại học Văn Lang', 'Luật', 'K44', '2002-12-12', 'Nữ', '987 Trần Hưng Đạo, Q5, TP.HCM', 'Sinh viên năm 5 Luật'],
            ['ST007', 'Vũ Hoàng Nam', 'Vũ Hoàng Nam', 'vuhoangnam@vttu.edu.vn', '0963210987', '2023001', '789012345678', 'Đại học Văn Lang', 'Du lịch', 'K47', '2005-04-18', 'Nam', '147 Nguyễn Trãi, Q1, TP.HCM', 'Sinh viên năm 1 Du lịch'],
            ['ST008', 'Ngô Ngọc Trâm', 'Ngô Ngọc Trâm', 'ngonogctram@vttu.edu.vn', '0972109876', '2021004', '890123456789', 'Đại học Văn Lang', 'Tài chính', 'K45', '2003-06-30', 'Nữ', '258 Bà Triệu, Q1, TP.HCM', 'Sinh viên năm 4 Tài chính'],
            ['ST009', 'Đình Quang Hà', 'Đình Quang Hà', 'dinhquangha@vttu.edu.vn', '0981098765', '2022002', '901234567890', 'Đại học Văn Lang', 'Marketing', 'K46', '2004-02-14', 'Nam', '369 Nguyễn Thị Minh Khai, Q1, TP.HCM', 'Sinh viên năm 2 Marketing'],
            ['ST010', 'Bùi Minh Anh', 'Bùi Minh Anh', 'buimhanh@vttu.edu.vn', '0990987654', '2020003', '012345678901', 'Đại học Văn Lang', 'Quốc tế học', 'K44', '2002-08-22', 'Nữ', '741 Hai Bà Trưng, Q1, TP.HCM', 'Sinh viên năm 5 QTH'],
            ['ST011', 'Phan Văn Hùng', 'Phan Văn Hùng', 'phanvanhung@vttu.edu.vn', '0909876543', '2021005', '123098765432', 'Đại học Văn Lang', 'Công nghệ thông tin', 'K45', '2003-05-17', 'Nam', '852 Võ Văn Kiệt, Q1, TP.HCM', 'Sinh viên năm 4 CNTT'],
            ['ST012', 'Lê Thị Thu', 'Lê Thị Thu', 'lethithu@vttu.edu.vn', '0918765432', '2021006', '234109876543', 'Đại học Văn Lang', 'Quản trị kinh doanh', 'K45', '2003-08-25', 'Nữ', '963 Hàm Nghi, Q1, TP.HCM', 'Sinh viên năm 4 QTDL'],
            ['ST013', 'Trần Minh Tuấn', 'Trần Minh Tuấn', 'tranminhtuan@vttu.edu.vn', '0927654321', '2020004', '345209876543', 'Đại học Văn Lang', 'Kiến trúc', 'K44', '2002-11-30', 'Nam', '147 Nguyễn Thị Định, Q2, TP.HCM', 'Sinh viên năm 5 Kiến trúc'],
            ['ST014', 'Hoàng Thị Mai', 'Hoàng Thị Mai', 'hoangthimai@vttu.edu.vn', '0936543210', '2022003', '456309876543', 'Đại học Văn Lang', 'Thiết kế đồ họa', 'K46', '2004-04-12', 'Nữ', '258 Ung Văn Khiêm, Q5, TP.HCM', 'Sinh viên năm 2 THDH'],
            ['ST015', 'Nguyễn Đức Anh', 'Nguyễn Đức Anh', 'nguyenducanh@vttu.edu.vn', '0945432109', '2021007', '567409876543', 'Đại học Văn Lang', 'Ngôn ngữ Anh', 'K45', '2003-07-19', 'Nam', '369 Nguyễn Trãi, Q1, TP.HCM', 'Sinh viên năm 4 NNA'],
            ['ST016', 'Vũ Thị Ngọc', 'Vũ Thị Ngọc', 'vuthingoc@vttu.edu.vn', '0954321098', '2020005', '678509876543', 'Đại học Văn Lang', 'Luật', 'K44', '2002-09-14', 'Nữ', '741 Lý Tự Trọng, Q1, TP.HCM', 'Sinh viên năm 5 Luật'],
            ['ST017', 'Đỗ Văn Huy', 'Đỗ Văn Huy', 'dovanvanhuy@vttu.edu.vn', '0963210987', '2023002', '789609876543', 'Đại học Văn Lang', 'Du lịch', 'K47', '2005-01-28', 'Nam', '852 Nguyễn Trãi, Q1, TP.HCM', 'Sinh viên năm 1 Du lịch'],
            ['ST018', 'Bùi Thanh Tâm', 'Bùi Thanh Tâm', 'buithanhtam@vttu.edu.vn', '0972109876', '2021008', '890709876543', 'Đại học Văn Lang', 'Tài chính', 'K45', '2003-12-03', 'Nữ', '963 Đồng Khởi, Q1, TP.HCM', 'Sinh viên năm 4 Tài chính'],
            ['ST019', 'Phạm Quốc Bảo', 'Phạm Quoc Bảo', 'phamquocbao@vttu.edu.vn', '0981098765', '2022004', '901809876543', 'Đại học Văn Lang', 'Marketing', 'K46', '2004-06-21', 'Nam', '147 Nguyễn Huệ, Q1, TP.HCM', 'Sinh viên năm 2 Marketing'],
            ['ST020', 'Lê Thị Diễm My', 'Lê Thị Diễm My', 'lethidiemy@vttu.edu.vn', '0990987654', '2020006', '012909876543', 'Đại học Văn Lang', 'Quốc tế học', 'K44', '2002-10-07', 'Nữ', '258 Lê Thánh Tôn, Q1, TP.HCM', 'Sinh viên năm 5 QTH'],
            ['ST021', 'Trần Hoàng Phúc', 'Trần Hoàng Phúc', 'tranhoangphuc@vttu.edu.vn', '0908765432', '2021009', '123019876543', 'Đại học Văn Lang', 'Công nghệ thông tin', 'K45', '2003-02-15', 'Nam', '369 Nguyễn Thị Minh Khai, Q1, TP.HCM', 'Sinh viên năm 4 CNTT'],
            ['ST022', 'Nguyễn Thu Trang', 'Nguyễn Thu Trang', 'nguyenthutrang@vttu.edu.vn', '0917654321', '2021010', '234119876543', 'Đại học Văn Lang', 'Quản trị kinh doanh', 'K45', '2003-05-28', 'Nữ', '741 Hai Bà Trưng, Q1, TP.HCM', 'Sinh viên năm 4 QTDL'],
            ['ST023', 'Lê Văn Nam', 'Lê Văn Nam', 'levannam@vttu.edu.vn', '0926543210', '2020007', '345129876543', 'Đại học Văn Lang', 'Kiến trúc', 'K44', '2002-08-11', 'Nam', '123 Nguyễn Huệ, Q1, TP.HCM', 'Sinh viên năm 5 Kiến trúc'],
            ['ST024', 'Phạm Thị Hoa', 'Phạm Thị Hoa', 'phamthihoa@vttu.edu.vn', '0935432109', '2022005', '456139876543', 'Đại học Văn Lang', 'Thiết kế đồ họa', 'K46', '2004-03-24', 'Nữ', '456 Lê Lợi, Q3, TP.HCM', 'Sinh viên năm 2 THDH']
        ];

        // Add sample data to sheet
        $row = 2;
        foreach ($sampleData as $dataRow) {
            $col = 'A';
            foreach ($dataRow as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'O') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Add some styling
        $sheet->getStyle('A2:O' . ($row - 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        
        // Add border to data range
        $sheet->getStyle('A1:O' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);
    }
}
