<?php

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Create new spreadsheet
$spreadsheet = new Spreadsheet();
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
    $sheet->getStyle($col . '1')->getFill()->setFillType(Fill::FILL_SOLID);
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
$sheet->getStyle('A2:O' . ($row - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Add border to data range
$sheet->getStyle('A1:O' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Save file
$writer = new Xlsx($spreadsheet);
$filename = 'patron_import_template_36_rows.xlsx';
$writer->save($filename);

echo "Excel file created: $filename\n";
echo "Total rows: " . (count($sampleData) + 1) . " (including header)\n";
echo "Teachers: 12 rows (GV001-GV012)\n";
echo "Students: 24 rows (ST001-ST024)\n";

?>
