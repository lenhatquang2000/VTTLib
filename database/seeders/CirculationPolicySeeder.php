<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CirculationPolicy;
use App\Models\PatronGroup;

class CirculationPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get patron groups
        $studentGroup = PatronGroup::where('code', 'SV')->first();
        $facultyGroup = PatronGroup::where('code', 'GV')->first();
        $guestGroup = PatronGroup::where('code', 'KHACH')->first();

        // Student Policy
        if ($studentGroup) {
            CirculationPolicy::create([
                'name' => 'Chính sách Sinh viên',
                'patron_group_id' => $studentGroup->id,
                
                // Loan settings
                'max_loan_days' => 14,
                'max_items' => 5,
                'max_renewals' => 2,
                'renewal_days' => 7,
                
                // Fine settings
                'fine_per_day' => 1000.00,
                'max_fine' => 100000.00,
                'grace_period_days' => 2,
                
                // Reservation settings
                'can_reserve' => true,
                'max_reservations' => 3,
                'reservation_hold_days' => 3,
                
                // Reading Room Policies
                'can_use_reading_room' => true,
                'max_reading_room_items' => 5,
                'reading_room_hours' => 4,
                'reading_room_due_time' => '17:00:00',
                'reading_room_fine_per_hour' => 5000.00,
                'reading_room_max_fine' => 50000.00,
                
                // Hold/Reserve Policies
                'can_place_hold' => true,
                'max_holds' => 3,
                'hold_expiry_days' => 7,
                'hold_notification_days' => 2,
                'hold_cancellation_fee' => 0.00,
                'allow_hold_renewal' => false,
                'max_hold_renewals' => 0,
                
                // Restrictions
                'max_outstanding_fine' => 50000.00,
                'is_active' => true,
                'notes' => 'Chính sách mặc định cho sinh viên'
            ]);
        }

        // Faculty Policy
        if ($facultyGroup) {
            CirculationPolicy::create([
                'name' => 'Chính sách Giảng viên',
                'patron_group_id' => $facultyGroup->id,
                
                // Loan settings
                'max_loan_days' => 30,
                'max_items' => 10,
                'max_renewals' => 5,
                'renewal_days' => 15,
                
                // Fine settings
                'fine_per_day' => 2000.00,
                'max_fine' => 200000.00,
                'grace_period_days' => 5,
                
                // Reservation settings
                'can_reserve' => true,
                'max_reservations' => 5,
                'reservation_hold_days' => 7,
                
                // Reading Room Policies
                'can_use_reading_room' => true,
                'max_reading_room_items' => 10,
                'reading_room_hours' => 8,
                'reading_room_due_time' => '20:00:00',
                'reading_room_fine_per_hour' => 10000.00,
                'reading_room_max_fine' => 100000.00,
                
                // Hold/Reserve Policies
                'can_place_hold' => true,
                'max_holds' => 5,
                'hold_expiry_days' => 14,
                'hold_notification_days' => 3,
                'hold_cancellation_fee' => 0.00,
                'allow_hold_renewal' => true,
                'max_hold_renewals' => 2,
                
                // Restrictions
                'max_outstanding_fine' => 100000.00,
                'is_active' => true,
                'notes' => 'Chính sách ưu đãi cho giảng viên'
            ]);
        }

        // Guest Policy
        if ($guestGroup) {
            CirculationPolicy::create([
                'name' => 'Chính sách Khách',
                'patron_group_id' => $guestGroup->id,
                
                // Loan settings
                'max_loan_days' => 7,
                'max_items' => 2,
                'max_renewals' => 1,
                'renewal_days' => 3,
                
                // Fine settings
                'fine_per_day' => 5000.00,
                'max_fine' => 50000.00,
                'grace_period_days' => 0,
                
                // Reservation settings
                'can_reserve' => false,
                'max_reservations' => 0,
                'reservation_hold_days' => 0,
                
                // Reading Room Policies
                'can_use_reading_room' => true,
                'max_reading_room_items' => 2,
                'reading_room_hours' => 2,
                'reading_room_due_time' => '16:00:00',
                'reading_room_fine_per_hour' => 10000.00,
                'reading_room_max_fine' => 20000.00,
                
                // Hold/Reserve Policies
                'can_place_hold' => false,
                'max_holds' => 0,
                'hold_expiry_days' => 0,
                'hold_notification_days' => 0,
                'hold_cancellation_fee' => 0.00,
                'allow_hold_renewal' => false,
                'max_hold_renewals' => 0,
                
                // Restrictions
                'max_outstanding_fine' => 10000.00,
                'is_active' => true,
                'notes' => 'Chính sách giới hạn cho khách vãng lai'
            ]);
        }

        $this->command->info('Circulation policies seeded successfully!');
    }
}
