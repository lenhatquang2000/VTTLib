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
use App\Services\BarcodeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatronController extends Controller
{
    protected $barcodeService;

    public function __construct(BarcodeService $barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    public function index()
    {
        $patrons = PatronDetail::with('user')->latest()->paginate(15);
        $barcodeService = $this->barcodeService; // For rendering in view
        return view('admin.patrons.index', compact('patrons', 'barcodeService'));
    }

    public function create()
    {
        $branches = Branch::all();
        $nextCode = $this->barcodeService->getNextCode('patron');
        
        $classifications = [
            'student' => __('Sinh viên'),
            'lecturer' => __('Giảng viên'),
            'staff' => __('Cán bộ'),
            'manager' => __('Ban giám hiệu'),
            'other' => __('Khác')
        ];
        
        return view('admin.patrons.create', compact('branches', 'classifications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Mandatory
            'patron_code' => 'required|string|unique:patron_details,patron_code',
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'expiry_date' => 'required|date',
            
            // Optional Identity
            'mssv' => 'nullable|string|max:50|unique:patron_details,mssv',
            'id_card' => 'nullable|string|max:20',
            'phone_contact' => 'nullable|string|max:50',
            'classification' => 'nullable|string',
            
            // Personal
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'profile_image' => 'nullable|image|max:2048',
            
            // Organization
            'school_name' => 'nullable|string|max:255',
            'batch' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'position_class' => 'nullable|string|max:255',
            
            // Contact
            'phone' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'address1' => 'nullable|string|max:500',
            'address2' => 'nullable|string|max:500',
            'branch' => 'nullable|string',
            
            // System/Status
            'card_status' => 'required|string',
            'is_read_only' => 'boolean',
            'is_waiting_for_print' => 'boolean',
            'registration_date' => 'required|date',
            
            // Financial
            'card_fee' => 'nullable|numeric',
            'deposit' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'notes' => 'nullable|string'
        ]);

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
                'card_status' => $validated['card_status'],
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
                'classification' => $validated['classification'] ?? 'individual',
                'card_fee' => $validated['card_fee'] ?? 0,
                'deposit' => $validated['deposit'] ?? 0,
                'balance' => $validated['balance'] ?? 0,
                'registration_date' => $validated['registration_date'],
                'expiry_date' => $validated['expiry_date'],
                'creator_id' => auth()->id(),
                'notes' => $validated['notes'] ?? null,
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
}
