<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Field;
use App\Models\User;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get admin user and fields
        $admin = User::where('email', 'admin@example.com')->first();
        $fields = Field::all();

        if (!$admin || $fields->count() === 0) {
            $this->command->error('Please run RolePermissionSeeder and FieldSeeder first!');
            return;
        }

        $schedules = [
            // Daily maintenance - recurring
            [
                'field_id' => $fields->first()->id,
                'date' => today(),
                'start_time' => '06:00:00',
                'end_time' => '07:00:00',
                'type' => 'maintenance',
                'title' => 'Daily Field Cleaning',
                'description' => 'Routine morning cleaning and field preparation',
                'is_recurring' => true,
                'recurring_type' => 'daily',
                'recurring_end_date' => today()->addMonth(),
                'recurring_days' => null,
                'created_by' => $admin->id,
            ],

            // Weekly maintenance - specific days
            [
                'field_id' => $fields->get(1)->id,
                'date' => today()->startOfWeek()->addDay(), // Tuesday
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'type' => 'maintenance',
                'title' => 'Weekly Deep Cleaning',
                'description' => 'Deep cleaning, grass maintenance, and facility inspection',
                'is_recurring' => true,
                'recurring_type' => 'weekly',
                'recurring_end_date' => today()->addMonths(3),
                'recurring_days' => ['2', '5'], // Tuesday and Friday
                'created_by' => $admin->id,
            ],

            // Special event - one time
            [
                'field_id' => $fields->get(2)->id,
                'date' => today()->addDays(7),
                'start_time' => '18:00:00',
                'end_time' => '22:00:00',
                'type' => 'event',
                'title' => 'Corporate Tournament',
                'description' => 'Annual company tournament - field reserved for exclusive use',
                'is_recurring' => false,
                'recurring_type' => null,
                'recurring_end_date' => null,
                'recurring_days' => null,
                'created_by' => $admin->id,
            ],

            // Renovation - multi-day
            [
                'field_id' => $fields->get(3)->id,
                'date' => today()->addDays(14),
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'type' => 'renovation',
                'title' => 'Goal Post Replacement',
                'description' => 'Installing new professional goal posts and safety nets',
                'is_recurring' => false,
                'recurring_type' => null,
                'recurring_end_date' => null,
                'recurring_days' => null,
                'created_by' => $admin->id,
            ],

            // Private use - weekend recurring
            [
                'field_id' => $fields->first()->id,
                'date' => today()->next(Carbon::SATURDAY),
                'start_time' => '20:00:00',
                'end_time' => '22:00:00',
                'type' => 'private',
                'title' => 'Private Training Session',
                'description' => 'Weekly training for local football academy',
                'is_recurring' => true,
                'recurring_type' => 'weekly',
                'recurring_end_date' => today()->addMonths(2),
                'recurring_days' => ['6', '0'], // Saturday and Sunday
                'created_by' => $admin->id,
            ],

            // Emergency maintenance - today
            [
                'field_id' => $fields->get(1)->id,
                'date' => today(),
                'start_time' => '12:00:00',
                'end_time' => '13:00:00',
                'type' => 'blocked',
                'title' => 'Emergency Repair',
                'description' => 'Fixing damaged sprinkler system',
                'is_recurring' => false,
                'recurring_type' => null,
                'recurring_end_date' => null,
                'recurring_days' => null,
                'created_by' => $admin->id,
            ],

            // Cleaning schedule - weekdays
            [
                'field_id' => $fields->get(2)->id,
                'date' => today()->next(Carbon::MONDAY),
                'start_time' => '22:00:00',
                'end_time' => '23:00:00',
                'type' => 'cleaning',
                'title' => 'Evening Deep Clean',
                'description' => 'End-of-day field cleaning and sanitization',
                'is_recurring' => true,
                'recurring_type' => 'weekly',
                'recurring_end_date' => today()->addMonths(6),
                'recurring_days' => ['1', '2', '3', '4', '5'], // Monday to Friday
                'created_by' => $admin->id,
            ],

            // Past maintenance - for historical data
            [
                'field_id' => $fields->get(3)->id,
                'date' => today()->subDays(3),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'type' => 'maintenance',
                'title' => 'Field Line Repainting',
                'description' => 'Repainting field boundaries and center circle',
                'is_recurring' => false,
                'recurring_type' => null,
                'recurring_end_date' => null,
                'recurring_days' => null,
                'created_by' => $admin->id,
            ],

            // Monthly equipment check
            [
                'field_id' => $fields->first()->id,
                'date' => today()->addDays(30),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'type' => 'maintenance',
                'title' => 'Monthly Equipment Inspection',
                'description' => 'Safety inspection of all field equipment and facilities',
                'is_recurring' => true,
                'recurring_type' => 'monthly',
                'recurring_end_date' => today()->addYear(),
                'recurring_days' => null,
                'created_by' => $admin->id,
            ],

            // Weekend tournament block
            [
                'field_id' => $fields->get(1)->id,
                'date' => today()->next(Carbon::SUNDAY),
                'start_time' => '08:00:00',
                'end_time' => '18:00:00',
                'type' => 'event',
                'title' => 'Inter-School Championship',
                'description' => 'Full day tournament - all regular bookings cancelled',
                'is_recurring' => false,
                'recurring_type' => null,
                'recurring_end_date' => null,
                'recurring_days' => null,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($schedules as $scheduleData) {
            Schedule::create($scheduleData);
        }
    }
}
