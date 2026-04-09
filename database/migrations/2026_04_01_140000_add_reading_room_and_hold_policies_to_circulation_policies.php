<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('circulation_policies', function (Blueprint $table) {
            // Reading Room Policies
            $table->boolean('can_use_reading_room')->default(true)->after('max_outstanding_fine');
            $table->integer('max_reading_room_items')->default(5)->after('can_use_reading_room');
            $table->integer('reading_room_hours')->default(4)->after('max_reading_room_items');
            $table->time('reading_room_due_time')->default('17:00:00')->after('reading_room_hours');
            $table->decimal('reading_room_fine_per_hour', 10, 2)->default(5000)->after('reading_room_due_time');
            $table->decimal('reading_room_max_fine', 10, 2)->default(50000)->after('reading_room_fine_per_hour');
            
            // Hold/Reserve Policies
            $table->boolean('can_place_hold')->default(true)->after('reading_room_max_fine');
            $table->integer('max_holds')->default(3)->after('can_place_hold');
            $table->integer('hold_expiry_days')->default(7)->after('max_holds');
            $table->integer('hold_notification_days')->default(2)->after('hold_expiry_days');
            $table->decimal('hold_cancellation_fee', 10, 2)->default(0)->after('hold_notification_days');
            $table->boolean('allow_hold_renewal')->default(false)->after('hold_cancellation_fee');
            $table->integer('max_hold_renewals')->default(0)->after('allow_hold_renewal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('circulation_policies', function (Blueprint $table) {
            $table->dropColumn([
                'can_use_reading_room',
                'max_reading_room_items',
                'reading_room_hours',
                'reading_room_due_time',
                'reading_room_fine_per_hour',
                'reading_room_max_fine',
                'can_place_hold',
                'max_holds',
                'hold_expiry_days',
                'hold_notification_days',
                'hold_cancellation_fee',
                'allow_hold_renewal',
                'max_hold_renewals'
            ]);
        });
    }
};
