<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatronDetail;
use App\Models\User;
use App\Models\Role;
use App\Models\Branch;
use App\Models\PatronAddress;
use App\Models\ActivityLog;
use App\Models\PatronGroup;
use App\Models\PatronLockHistory;
use App\Services\BarcodeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PatronController extends Controller
{
    protected $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    public function index(Request $request)
    {
        // Get search and filter parameters
        $search = $request->get('search', '');
        $searchField = $request->get('search_field', 'all'); // all, patron_code, name, email, phone, address
        $status = $request->get('status', 'all'); // all, active, locked
        $patronGroup = $request->get('patron_group', 'all');
        $branch = $request->get('branch', 'all');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        $viewMode = $request->get('view_mode', 'card'); // card, grid, list
        $perPage = $request->get('per_page', 15);
        $sort = $request->get('sort', 'desc'); // desc, asc

        // Build query
        $query = PatronDetail::with(['user', 'patronGroup']);

        // Search functionality
        if (!empty($search)) {
            switch ($searchField) {
                case 'patron_code':
                    $query->where('patron_code', 'LIKE', "%{$search}%");
                    break;
                case 'name':
                    $query->where('display_name', 'LIKE', "%{$search}%");
                    break;
                case 'email':
                    $query->whereHas('user', function($q) use ($search) {
                        $q->where('email', 'LIKE', "%{$search}%");
                    });
                    break;
                case 'phone':
                    $query->where('phone', 'LIKE', "%{$search}%");
                    break;
                case 'address':
                    $query->whereHas('addresses', function($q) use ($search) {
                        $q->where('address_line', 'LIKE', "%{$search}%");
                    });
                    break;
                default: // all
                    $query->where(function($q) use ($search) {
                        $q->where('patron_code', 'LIKE', "%{$search}%")
                          ->orWhere('display_name', 'LIKE', "%{$search}%")
                          ->orWhere('phone', 'LIKE', "%{$search}%")
                          ->orWhereHas('user', function($subQ) use ($search) {
                              $subQ->where('email', 'LIKE', "%{$search}%");
                          })
                          ->orWhereHas('addresses', function($subQ) use ($search) {
                              $subQ->where('address_line', 'LIKE', "%{$search}%");
                          });
                    });
            }
        }

        // Status filter
        if ($status !== 'all') {
            $query->where('card_status', $status === 'active' ? 'normal' : 'locked');
        }

        // Patron group filter
        if ($patronGroup !== 'all') {
            $query->where('patron_group_id', $patronGroup);
        }

        // Branch filter - branch is a string field, not a relationship
        if ($branch !== 'all') {
            $query->where('branch', $branch);
        }

        // Date range filter
        if (!empty($dateFrom)) {
            $query->whereDate('registration_date', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $query->whereDate('registration_date', '<=', $dateTo);
        }

        // Order by sort parameter
        if ($sort === 'asc') {
            $query->orderBy('registration_date', 'asc');
        } else {
            $query->orderBy('registration_date', 'desc');
        }
        
        $patrons = $query->paginate($perPage)->withQueryString();

        // Get filter data
        $patronGroups = PatronGroup::where('is_active', true)->get();
        $branches = Branch::all();

        $barcodeService = $this->barcodeService;

        return view('admin.patrons.index', compact(
            'patrons', 
            'barcodeService', 
            'patronGroups', 
            'branches',
            'viewMode',
            'search',
            'searchField',
            'status',
            'patronGroup',
            'branch',
            'dateFrom',
            'dateTo',
            'perPage'
        ));
    }

    public function create()
    {
        $branches = Branch::all();
        
        if (!$this->barcodeService->hasActiveRule('patron')) {
            session()->flash('warning', __('Hệ thống chưa thiết lập quy tắc mã vạch cho Bạn đọc'));
        }

        $nextCode = $this->barcodeService->getNextCode('patron');
        $patronGroups = PatronGroup::where('is_active', true)->get();
        
        return view('admin.patrons.create', compact('branches', 'patronGroups', 'nextCode'));
    }

    public function edit($id)
    {
        $patron = PatronDetail::findOrFail($id);
        $branches = Branch::all();
        $patronGroups = PatronGroup::where('is_active', true)->get();
        
        return view('admin.patrons.edit', compact('patron', 'branches', 'patronGroups'));
    }

    public function update(Request $request, $id)
    {
        $patron = PatronDetail::findOrFail($id);
        $user = $patron->user;

        // Lưu thông tin cũ để so sánh
        $oldData = [
            'patron' => $patron->toArray(),
            'user' => $user->toArray(),
        ];

        $validated = $request->validate([
            'patron_code' => 'required|string|unique:patron_details,patron_code,' . $patron->id,
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'patron_group_id' => 'required|exists:patron_groups,id',
            'registration_date' => 'required|date',
            'expiry_date' => 'required|date|after:registration_date',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'addresses' => 'nullable|array',
            'addresses.*' => 'nullable|string|max:500',
            'branch_id' => 'nullable|exists:branches,id',
            'department' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Update user information
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update patron information
        $patronData = [
            'patron_code' => $validated['patron_code'],
            'patron_group_id' => $validated['patron_group_id'],
            'registration_date' => $validated['registration_date'],
            'expiry_date' => $validated['expiry_date'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'branch_id' => $validated['branch_id'],
            'department' => $validated['department'],
            'notes' => $validated['notes'],
        ];

        // Handle addresses - take first address as primary
        if (isset($validated['addresses']) && is_array($validated['addresses']) && !empty($validated['addresses'])) {
            $patronData['address'] = $validated['addresses'][0];
        } else {
            $patronData['address'] = null;
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            
            // Sanitize filename - remove special characters
            $originalName = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();
            $sanitizedName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
            
            $destinationPath = 'public/patrons/avatars/' . $sanitizedName;
            $fullPath = storage_path('app/' . $destinationPath);
            
            // Use file_get_contents + file_put_contents method
            try {
                $fileContent = file_get_contents($image->getPathname());
                if ($fileContent === false) {
                    throw new \Exception('Cannot read temp file');
                }
                
                // Ensure directory exists
                $dir = dirname($fullPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                
                $result = file_put_contents($fullPath, $fileContent);
                if ($result === false) {
                    throw new \Exception('Cannot write to destination');
                }
                
            } catch (\Exception $e) {
                // Fallback to move method
                try {
                    $destinationDir = storage_path('app/public/patrons/avatars/');
                    if (!is_dir($destinationDir)) {
                        mkdir($destinationDir, 0755, true);
                    }
                    
                    $image->move($destinationDir, $sanitizedName);
                    
                } catch (\Exception $e2) {
                    return back()->with('error', 'Failed to upload image: ' . $e2->getMessage());
                }
            }
            
            // Delete old image if exists
            if ($patron->profile_image) {
                Storage::delete('public/' . $patron->profile_image);
            }
            
            $patronData['profile_image'] = 'patrons/avatars/' . $sanitizedName;
        }

        $patron->update($patronData);

        // Lưu thông tin mới để so sánh
        $newData = [
            'patron' => $patron->fresh()->toArray(),
            'user' => $user->fresh()->toArray(),
        ];

        // Tính toán các thay đổi
        $changes = $this->calculatePatronChanges($oldData, $newData);

        // Log activity với chi tiết thay đổi
        ActivityLog::log('patron_updated', $patron, [
            'patron_code' => $patron->patron_code,
            'display_name' => $patron->display_name,
            'changes' => $changes,
            'changed_fields' => array_keys($changes)
        ]);

        return redirect()->route('admin.patrons.index')
            ->with('success', __('Patron information updated successfully.'));
    }

    /**
     * Calculate changes between old and new patron data
     */
    private function calculatePatronChanges($oldData, $newData)
    {
        $changes = [];
        
        // Map Vietnamese field names to English for display
        $fieldNames = [
            'patron_code' => 'Mã độc giả',
            'name' => 'Họ và tên',
            'display_name' => 'Tên hiển thị',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'date_of_birth' => 'Ngày sinh',
            'gender' => 'Giới tính',
            'address' => 'Địa chỉ',
            'department' => 'Bộ phận',
            'notes' => 'Ghi chú',
            'patron_group_id' => 'Nhóm độc giả',
            'branch_id' => 'Chi nhánh',
            'registration_date' => 'Ngày đăng ký',
            'expiry_date' => 'Ngày hết hạn',
            'profile_image' => 'Ảnh đại diện'
        ];

        // Check user changes
        if (isset($oldData['user']) && isset($newData['user'])) {
            foreach ($oldData['user'] as $key => $oldValue) {
                if (isset($newData['user'][$key]) && $oldValue != $newData['user'][$key]) {
                    $fieldName = $fieldNames[$key] ?? $key;
                    $changes[$fieldName] = [
                        'old' => $oldValue,
                        'new' => $newData['user'][$key]
                    ];
                }
            }
        }

        // Check patron changes
        if (isset($oldData['patron']) && isset($newData['patron'])) {
            foreach ($oldData['patron'] as $key => $oldValue) {
                if (isset($newData['patron'][$key]) && $oldValue != $newData['patron'][$key]) {
                    $fieldName = $fieldNames[$key] ?? $key;
                    $changes[$fieldName] = [
                        'old' => $oldValue,
                        'new' => $newData['patron'][$key]
                    ];
                }
            }
        }

        return $changes;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patron_code' => 'required|string|unique:patron_details,patron_code|max:50',
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'patron_group_id' => 'required|exists:patron_groups,id',
            'registration_date' => 'required|date',
            'expiry_date' => 'required|date|after:registration_date',
            
            // Personal Information
            'id_card' => 'nullable|string|max:50',
            'mssv' => 'nullable|string|unique:patron_details,mssv|max:50',
            'phone_contact' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ], [
            'patron_code.required' => 'Mã độc giả không được để trống',
            'patron_code.unique' => 'Mã độc giả đã tồn tại trong hệ thống. Vui lòng chọn mã khác.',
            'patron_code.max' => 'Mã độc giả không được vượt quá 50 ký tự',
            'name.required' => 'Họ và tên không được để trống',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự',
            'display_name.required' => 'Tên hiển thị không được để trống',
            'display_name.max' => 'Tên hiển thị không được vượt quá 255 ký tự',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trong hệ thống. Vui lòng chọn email khác.',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'patron_group_id.required' => 'Nhóm độc giả không được để trống',
            'patron_group_id.exists' => 'Nhóm độc giả không hợp lệ',
            'registration_date.required' => 'Ngày đăng ký không được để trống',
            'registration_date.date' => 'Ngày đăng ký không đúng định dạng',
            'expiry_date.required' => 'Ngày hết hạn không được để trống',
            'expiry_date.date' => 'Ngày hết hạn không đúng định dạng',
            'expiry_date.after' => 'Ngày hết hạn phải sau ngày đăng ký',
            'mssv.unique' => 'MSSV đã tồn tại trong hệ thống. Vui lòng chọn MSSV khác.',
            'mssv.max' => 'MSSV không được vượt quá 50 ký tự',
            'gender.in' => 'Giới tính không hợp lệ',
        ]);

        // Additional validation rules
        $additionalValidated = $request->validate([
            'school_name' => 'nullable|string|max:255',
            'batch' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:255',
            'position_class' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'classification' => 'nullable|string|max:255',
            
            // Financial
            'card_fee' => 'nullable|numeric',
            'deposit' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'notes' => 'nullable|string'
        ]);

        // Merge all validated data
        $validated = array_merge($validated, $additionalValidated);

        $patron = DB::transaction(function () use ($request, $validated) {
            // 1. User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $patronRole = Role::firstOrCreate(['name' => 'visitor']);
            $user->roles()->attach($patronRole->id);

            // 2. Image
            $imagePath = null;
            if ($request->hasFile('profile_image')) {
                $imagePath = $request->file('profile_image')->store('patrons/avatars', 'public');
            }

            // 3. Patron Detail
            $patron = PatronDetail::create([
                'user_id' => $user->id,
                'patron_code' => $validated['patron_code'],
                'id_card' => $validated['id_card'] ?? null,
                'mssv' => $validated['mssv'] ?? null,
                'phone_contact' => $validated['phone_contact'] ?? null,
                'display_name' => $validated['display_name'],
                'card_status' => 'normal',
                'is_read_only' => $request->boolean('is_read_only'),
                'is_waiting_for_print' => $request->boolean('is_waiting_for_print'),
                'dob' => $validated['dob'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'profile_image' => $imagePath,
                'school_name' => $validated['school_name'] ?? null,
                'batch' => $validated['batch'] ?? null,
                'department' => $validated['department'] ?? null,
                'position_class' => $validated['position_class'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'fax' => $validated['fax'] ?? null,
                'branch' => $validated['branch'] ?? 'all',
                'patron_group_id' => $validated['patron_group_id'],
                'classification' => $validated['classification'] ?? 'individual',
                'card_fee' => $validated['card_fee'] ?? 0,
                'deposit' => $validated['deposit'] ?? 0,
                'balance' => $validated['balance'] ?? 0,
                'registration_date' => $validated['registration_date'],
                'expiry_date' => $validated['expiry_date'],
                'creator_id' => auth()->id(),
                'notes' => $validated['notes'] ?? null,
                'is_reading_room_only' => $request->boolean('is_reading_room_only'),
                'add_to_print_queue' => $request->boolean('add_to_print_queue'),
            ]);

            // 4. Multiple Addresses Handling
            if ($request->has('addresses')) {
                foreach ($request->addresses as $index => $addressLine) {
                    if (!empty($addressLine)) {
                        PatronAddress::create([
                            'patron_detail_id' => $patron->id,
                            'address_line' => $addressLine,
                            'type' => 'home',
                            'is_primary' => ($index === 0) // First address is primary
                        ]);
                    }
                }
            }

            ActivityLog::log('patron_created', $patron, ['name' => $validated['name']]);

            // 5. Update Barcode Counter and Save File
            $this->barcodeService->incrementCounter('patron', $validated['patron_code']);
            $this->barcodeService->saveAsFile(
                $validated['patron_code'], 
                'patrons/barcodes/' . $validated['patron_code'] . '.svg'
            );

            return $patron;
        });

        return redirect()->route('admin.patrons.index')->with('success', __('Patron created successfully.'));
    }

    public function toggleStatus($id)
    {
        $patron = PatronDetail::findOrFail($id);
        $oldStatus = $patron->card_status;
        $newStatus = ($oldStatus == 'normal') ? 'locked' : 'normal';
        
        $patron->update(['card_status' => $newStatus]);
        
        ActivityLog::log('patron_status_toggled', $patron, [
            'from' => $oldStatus,
            'to' => $newStatus
        ]);

        return back()->with('success', __('Status updated to :status', ['status' => __($newStatus)]));
    }

    public function renew(Request $request, $id)
    {
        $request->validate(['expiry_date' => 'required|date']);
        
        $patron = PatronDetail::findOrFail($id);
        $oldExpiry = $patron->expiry_date;
        $patron->update(['expiry_date' => $request->expiry_date]);
        
        ActivityLog::log('patron_renewed', $patron, [
            'old_expiry' => $oldExpiry,
            'new_expiry' => $request->expiry_date
        ]);

        return back()->with('success', __('Card renewed successfully until :date', ['date' => $request->expiry_date]));
    }

    public function destroy($id)
    {
        $patron = PatronDetail::findOrFail($id);
        $patron->delete();
        ActivityLog::log('patron_deleted', $patron);
        return redirect()->route('admin.patrons.index')->with('success', __('Patron moved to archives.'));
    }

    /**
     * Bulk update patrons
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'patron_ids' => 'required|array',
            'patron_ids.*' => 'exists:users,id',
            'fields' => 'required|array',
            'fields.*' => 'in:patron_group_id,branch_id,is_active,expiry_date,phone'
        ]);

        $patronIds = $request->patron_ids;
        $fields = $request->fields;
        $updateData = [];

        // Build update data based on selected fields
        foreach ($fields as $field) {
            if ($request->has($field) && $request->input($field) !== '') {
                $updateData[$field] = $request->input($field);
            }
        }

        if (empty($updateData)) {
            return back()->with('error', 'Vui lòng chọn ít nhất một trường và nhập giá trị mới.');
        }

        // Update patrons
        $updatedCount = User::whereIn('id', $patronIds)->update($updateData);

        // Log activity
        ActivityLog::log('patrons_bulk_updated', null, [
            'patron_count' => $updatedCount,
            'updated_fields' => array_keys($updateData)
        ]);

        return back()->with('success', "Đã cập nhật thông tin cho {$updatedCount} độc giả thành công.");
    }

    /**
     * Bulk delete patrons
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'patron_ids' => 'required|array',
            'patron_ids.*' => 'exists:users,id'
        ]);

        $patronIds = $request->patron_ids;
        
        // Get patrons before deletion for logging
        $patrons = User::whereIn('id', $patronIds)->get(['name', 'email']);
        
        // Soft delete patrons
        $deletedCount = User::whereIn('id', $patronIds)->delete();

        // Log activity
        ActivityLog::log('patrons_bulk_deleted', null, [
            'patron_count' => $deletedCount,
            'deleted_patrons' => $patrons->pluck('name')->toArray()
        ]);

        return back()->with('success', "Đã xóa {$deletedCount} độc giả thành công.");
    }

    /**
     * Lock patron card
     */
    public function lock(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $patron = PatronDetail::findOrFail($id);
        
        if ($patron->isLocked()) {
            return back()->with('error', 'Thẻ độc giả đã bị khóa.');
        }

        $patron->lock($request->reason, auth()->id());

        return back()->with('success', 'Đã khóa thẻ độc giả thành công.');
    }

    /**
     * Unlock patron card
     */
    public function unlock(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'unlock_fee' => 'nullable|numeric|min:0'
        ]);

        $patron = PatronDetail::findOrFail($id);
        
        if (!$patron->isLocked()) {
            return back()->with('error', 'Thẻ độc giả không bị khóa.');
        }

        $unlockFee = $request->unlock_fee ?? 0;
        
        // Check if patron has enough balance for unlock fee
        if ($unlockFee > 0 && $patron->balance < $unlockFee) {
            return back()->with('error', 'Độc giả không đủ số dư để trả phí mở khóa.');
        }

        $patron->unlock($request->reason, auth()->id(), $unlockFee);

        return back()->with('success', 'Đã mở khóa thẻ độc giả thành công.');
    }

    /**
     * Add patron to print queue
     */
    public function addToPrintQueue($id)
    {
        $patron = PatronDetail::findOrFail($id);
        
        if ($patron->isInPrintQueue()) {
            return back()->with('error', 'Độc giả đã có trong danh sách chờ in.');
        }

        $patron->addToPrintQueue(auth()->id());

        return back()->with('success', 'Đã thêm độc giả vào danh sách chờ in.');
    }

    /**
     * Remove patron from print queue
     */
    public function removeFromPrintQueue($id)
    {
        $patron = PatronDetail::findOrFail($id);
        
        if (!$patron->isInPrintQueue()) {
            return back()->with('error', 'Độc giả không có trong danh sách chờ in.');
        }

        $patron->removeFromPrintQueue();

        return back()->with('success', 'Đã xóa độc giả khỏi danh sách chờ in.');
    }

    /**
     * Show patron lock history
     */
    public function lockHistory($id)
    {
        $patron = PatronDetail::findOrFail($id);
        $lockHistory = $patron->lockHistory()->orderBy('created_at', 'desc')->get();

        return view('admin.patrons.lock-history', compact('patron', 'lockHistory'));
    }

    /**
     * Show all lock history
     */
    public function allLockHistory(Request $request)
    {
        $query = PatronLockHistory::with(['patron', 'lockedBy', 'unlockedBy'])
            ->orderBy('created_at', 'desc');

        // Filter by patron code if provided
        if ($request->patron_code) {
            $query->whereHas('patron', function($q) use ($request) {
                $q->where('patron_code', 'like', '%' . $request->patron_code . '%');
            });
        }

        // Filter by date range if provided
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $lockHistory = $query->paginate(20);

        return view('admin.patrons.all-lock-history', compact('lockHistory'));
    }

    /**
     * Show patron system logs
     */
    public function systemLogs(Request $request)
    {
        $query = ActivityLog::where(function($q) {
            // Chỉ lấy logs liên quan đến patron
            $q->where('action', 'like', '%patron%')
              ->orWhere('model_type', 'like', '%Patron%')
              ->orWhere('model_type', 'like', '%patron%')
              ->orWhere('details', 'like', '%patron%')
              ->orWhere('details', 'like', '%độc giả%')
              ->orWhere('details', 'like', '%bạn đọc%');
        })->orderBy('created_at', 'desc');

        // Filter by log type if provided
        if ($request->log_type) {
            $query->where('action', 'like', '%' . $request->log_type . '%');
        }

        // Filter by date range if provided
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by user if provided
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(20);
        $users = User::orderBy('name')->get();

        return view('admin.patrons.system-logs', compact('logs', 'users'));
    }
}
