<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CirculationPolicy;
use App\Models\PatronGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CirculationPolicyController extends Controller
{
    /**
     * Display a listing of circulation policies
     */
    public function index()
    {
        $policies = CirculationPolicy::with('patronGroup')
            ->orderBy('patron_group_id')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.circulation.policies.index', compact('policies'));
    }

    /**
     * Show the form for creating a new circulation policy
     */
    public function create()
    {
        $patronGroups = PatronGroup::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.circulation.policies.create', compact('patronGroups'));
    }

    /**
     * Store a newly created circulation policy
     */
    public function store(Request $request)
    {
        // Debug: Log all request data
        \Log::info('Request data:', $request->all());
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'patron_group_id' => 'required|exists:patron_groups,id',
            
            // Loan settings
            'max_loan_days' => 'required|integer|min:1|max:365',
            'max_items' => 'required|integer|min:1|max:50',
            'max_renewals' => 'required|integer|min:0|max:10',
            'renewal_days' => 'required|integer|min:1|max:90',
            
            // Fine settings
            'fine_per_day' => 'required|numeric|min:0|max:100000',
            'max_fine' => 'required|numeric|min:0|max:1000000',
            'grace_period_days' => 'required|integer|min:0|max:30',
            
            // Reservation settings
            'can_reserve' => 'nullable|string',
            'max_reservations' => 'required|integer|min:0|max:20',
            'reservation_hold_days' => 'required|integer|min:1|max:30',
            
            // Reading Room Policies
            'can_use_reading_room' => 'nullable|string',
            'max_reading_room_items' => 'required|integer|min:0|max:20',
            'reading_room_hours' => 'required|integer|min:1|max:24',
            'reading_room_due_time' => 'nullable|date_format:H:i',
            'reading_room_fine_per_hour' => 'required|numeric|min:0|max:100000',
            'reading_room_max_fine' => 'required|numeric|min:0|max:1000000',
            
            // Hold/Reserve Policies
            'can_place_hold' => 'nullable|string',
            'max_holds' => 'required|integer|min:0|max:20',
            'hold_expiry_days' => 'required|integer|min:1|max:90',
            'hold_notification_days' => 'required|integer|min:0|max:30',
            'hold_cancellation_fee' => 'required|numeric|min:0|max:100000',
            'allow_hold_renewal' => 'nullable|string',
            'max_hold_renewals' => 'required|integer|min:0|max:10',
            
            // Restrictions
            'max_outstanding_fine' => 'required|numeric|min:0|max:1000000',
            'is_active' => 'nullable|string',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Convert boolean values
        $validated['can_reserve'] = $request->has('can_reserve');
        $validated['can_use_reading_room'] = $request->has('can_use_reading_room');
        $validated['can_place_hold'] = $request->has('can_place_hold');
        $validated['allow_hold_renewal'] = $request->has('allow_hold_renewal');
        $validated['is_active'] = $request->has('is_active');

        CirculationPolicy::create($validated);

        return redirect()
            ->route('admin.circulation.policies.index')
            ->with('success', __('Tạo chính sách lưu thông thành công.'));
    }

    /**
     * Display the specified circulation policy
     */
    public function show(CirculationPolicy $policy)
    {
        $policy->load('patronGroup');
        $policySummary = $policy->getPolicySummary();
        
        return view('admin.circulation.policies.show', compact('policy', 'policySummary'));
    }

    /**
     * Show the form for editing the specified circulation policy
     */
    public function edit(CirculationPolicy $policy)
    {
        // Debug: Log policy data
        \Log::info('Edit policy - Policy ID: ' . $policy->id);
        \Log::info('Edit policy - Policy data: ', $policy->toArray());
        
        $patronGroups = PatronGroup::where('is_active', true)->orderBy('name')->get();
        
        \Log::info('Edit policy - Patron groups count: ' . $patronGroups->count());
        
        return view('admin.circulation.policies.edit', compact('policy', 'patronGroups'));
    }

    /**
     * Update the specified circulation policy
     */
    public function update(Request $request, CirculationPolicy $policy)
    {
        // Debug: Log all request data
        \Log::info('Update request data:', $request->all());
        \Log::info('Reading room due time from request: ' . $request->input('reading_room_due_time'));
        \Log::info('Reading room due time type: ' . gettype($request->input('reading_room_due_time')));
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'patron_group_id' => 'required|exists:patron_groups,id',
                
                // Loan settings
                'max_loan_days' => 'required|integer|min:1|max:365',
                'max_items' => 'required|integer|min:1|max:50',
                'max_renewals' => 'required|integer|min:0|max:10',
                'renewal_days' => 'required|integer|min:1|max:90',
                
                // Fine settings
                'fine_per_day' => 'required|numeric|min:0|max:100000',
                'max_fine' => 'required|numeric|min:0|max:1000000',
                'grace_period_days' => 'required|integer|min:0|max:30',
                
                // Reservation settings
                'can_reserve' => 'nullable|string',
                'max_reservations' => 'required|integer|min:0|max:20',
                'reservation_hold_days' => 'required|integer|min:1|max:30',
                
                // Reading Room Policies
                'can_use_reading_room' => 'nullable|string',
                'max_reading_room_items' => 'required|integer|min:0|max:20',
                'reading_room_hours' => 'required|integer|min:1|max:24',
                'reading_room_due_time' => 'nullable|date_format:H:i',
                'reading_room_fine_per_hour' => 'required|numeric|min:0|max:100000',
                'reading_room_max_fine' => 'required|numeric|min:0|max:1000000',
                
                // Hold/Reserve Policies
                'can_place_hold' => 'nullable|string',
                'max_holds' => 'required|integer|min:0|max:20',
                'hold_expiry_days' => 'required|integer|min:1|max:90',
                'hold_notification_days' => 'required|integer|min:0|max:30',
                'hold_cancellation_fee' => 'required|numeric|min:0|max:100000',
                'allow_hold_renewal' => 'nullable|string',
                'max_hold_renewals' => 'required|integer|min:0|max:10',
                
                // Restrictions
                'max_outstanding_fine' => 'required|numeric|min:0|max:1000000',
                'is_active' => 'nullable|string',
                'notes' => 'nullable|string|max:1000'
            ]);
            
            \Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed: ' . json_encode($e->errors()));
            throw $e;
        }

        // Convert checkbox values to boolean
        $validated['can_reserve'] = $request->has('can_reserve');
        $validated['can_use_reading_room'] = $request->has('can_use_reading_room');
        $validated['can_place_hold'] = $request->has('can_place_hold');
        $validated['allow_hold_renewal'] = $request->has('allow_hold_renewal');
        $validated['is_active'] = $request->has('is_active');

        $policy->update($validated);

        return redirect()
            ->route('admin.circulation.policies.index')
            ->with('success', __('Cập nhật chính sách lưu thông thành công.'));
    }

    /**
     * Remove the specified circulation policy
     */
    public function destroy(CirculationPolicy $policy)
    {
        try {
            DB::beginTransaction();
            
            // Check for related data and provide options
            $loanTransactions = $policy->loanTransactions()->count();
            $patronsWithPolicy = $policy->patronGroup ? $policy->patronGroup->patrons()->count() : 0;
            
            if ($loanTransactions > 0 || $patronsWithPolicy > 0) {
                // Create a summary of what will be affected
                $affectedData = [];
                
                if ($loanTransactions > 0) {
                    $affectedData[] = "{$loanTransactions} giao dịch mượn/trả";
                }
                
                if ($patronsWithPolicy > 0) {
                    $affectedData[] = "{$patronsWithPolicy} bạn đọc trong nhóm {$policy->patronGroup->name}";
                }
                
                // Soft delete instead of hard delete
                $policy->update([
                    'is_active' => false,
                    'notes' => ($policy->notes ?? '') . "\n\n[ĐÁNH DẤU XÓA: " . now()->format('d/m/Y H:i') . " - " . auth()->user()->name . "]\nLý do: " . implode(', ', $affectedData)
                ]);
                
                DB::commit();
                
                return redirect()
                    ->route('admin.circulation.policies.index')
                    ->with('success', __('Chính sách đã được vô hiệu hóa và đánh dấu xóa do có dữ liệu liên quan: :data', ['data' => implode(', ', $affectedData)]));
            }
            
            // If no related data, perform hard delete
            $policy->delete();
            
            DB::commit();
            
            return redirect()
                ->route('admin.circulation.policies.index')
                ->with('success', __('Xóa chính sách lưu thông thành công.'));
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('admin.circulation.policies.index')
                ->with('error', __('Không thể xóa chính sách: ') . $e->getMessage());
        }
    }

    /**
     * Toggle policy status
     */
    public function toggleStatus(CirculationPolicy $policy)
    {
        $policy->update(['is_active' => !$policy->is_active]);
        
        $status = $policy->is_active ? 'kích hoạt' : 'vô hiệu hóa';
        
        return redirect()
            ->route('admin.circulation.policies.index')
            ->with('success', __("Đã {$status} chính sách lưu thông thành công."));
    }

    /**
     * Force delete policy (for marked policies)
     */
    public function forceDelete(CirculationPolicy $policy)
    {
        try {
            DB::beginTransaction();
            
            // Only allow force delete for marked policies
            if (!str_contains($policy->notes ?? '', '[ĐÁNH DẤU XÓA:')) {
                throw new \Exception(__('Chỉ có thể xóa hoàn toàn các chính sách đã được đánh dấu xóa.'));
            }
            
            // Get related data counts for logging
            $loanTransactions = $policy->loanTransactions()->count();
            $patronsWithPolicy = $policy->patronGroup ? $policy->patronGroup->patrons()->count() : 0;
            
            // Create audit log before deletion
            $auditData = array(
                'policy_name' => $policy->name,
                'deleted_by' => auth()->user()->name,
                'deleted_at' => now()->format('d/m/Y H:i:s'),
                'related_data' => array(
                    'loan_transactions' => $loanTransactions,
                    'patrons_affected' => $patronsWithPolicy
                )
            );
            
            // Perform hard delete
            $policy->delete();
            
            DB::commit();
            
            return redirect()
                ->route('admin.circulation.policies.index')
                ->with('success', __('Đã xóa hoàn toàn chính sách ":policy" và tất cả dữ liệu liên quan.', ['policy' => $policy->name]));
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('admin.circulation.policies.index')
                ->with('error', __('Không thể xóa hoàn toàn chính sách: ') . $e->getMessage());
        }
    }

    /**
     * Duplicate a policy
     */
    public function duplicate(CirculationPolicy $policy)
    {
        $newPolicy = $policy->replicate();
        $newPolicy->name = $policy->name . ' (Bản sao)';
        $newPolicy->is_active = false;
        $newPolicy->save();

        return redirect()
            ->route('admin.circulation.policies.edit', $newPolicy)
            ->with('success', __('Sao chép chính sách lưu thông thành công. Vui lòng chỉnh sửa và kích hoạt khi cần.'));
    }
}
