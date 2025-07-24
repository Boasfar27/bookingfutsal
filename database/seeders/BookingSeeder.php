<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get users and fields
        $user = User::where('email', 'user@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();
        $fields = Field::all();

        if (!$user || !$admin || $fields->count() === 0) {
            $this->command->error('Please run RolePermissionSeeder and FieldSeeder first!');
            return;
        }

        // Create bookings for the past, present, and future
        $bookings = [
            // Past booking - completed
            [
                'user_id' => $user->id,
                'field_id' => $fields->first()->id,
                'booking_date' => Carbon::yesterday(),
                'start_time' => '19:00:00',
                'end_time' => '21:00:00',
                'duration_hours' => 2,
                'total_price' => $fields->first()->price_per_hour * 2,
                'status' => 'completed',
                'customer_name' => 'Ahmad Fadli',
                'customer_phone' => '081234567890',
                'customer_email' => 'ahmad.fadli@email.com',
                'notes' => 'Booking untuk latihan rutin tim',
                'admin_notes' => 'Customer reguler, sudah selesai main',
                'confirmed_at' => Carbon::yesterday()->subHours(6),
                'confirmed_by' => $admin->id,
            ],

            // Today booking - confirmed
            [
                'user_id' => $user->id,
                'field_id' => $fields->get(1)->id,
                'booking_date' => Carbon::today(),
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'duration_hours' => 2,
                'total_price' => $fields->get(1)->price_per_hour * 2,
                'status' => 'confirmed',
                'customer_name' => 'Sari Indah',
                'customer_phone' => '082345678901',
                'customer_email' => 'sari.indah@email.com',
                'notes' => 'Booking untuk acara kantor',
                'admin_notes' => 'Sudah transfer, confirmed',
                'confirmed_at' => Carbon::today()->subHours(2),
                'confirmed_by' => $admin->id,
            ],

            // Today booking - pending (waiting confirmation)
            [
                'user_id' => $user->id,
                'field_id' => $fields->get(2)->id,
                'booking_date' => Carbon::today(),
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'duration_hours' => 2,
                'total_price' => $fields->get(2)->price_per_hour * 2,
                'status' => 'pending',
                'customer_name' => 'Budi Santoso',
                'customer_phone' => '083456789012',
                'customer_email' => 'budi.santoso@email.com',
                'notes' => 'First time booking, mohon konfirmasi',
                'admin_notes' => null,
            ],

            // Tomorrow booking - confirmed
            [
                'user_id' => $user->id,
                'field_id' => $fields->first()->id,
                'booking_date' => Carbon::tomorrow(),
                'start_time' => '06:00:00',
                'end_time' => '08:00:00',
                'duration_hours' => 2,
                'total_price' => $fields->first()->price_per_hour * 2,
                'status' => 'confirmed',
                'customer_name' => 'Tim Futsal Garuda',
                'customer_phone' => '084567890123',
                'customer_email' => 'team.garuda@email.com',
                'notes' => 'Latihan pagi untuk persiapan turnamen',
                'admin_notes' => 'Tim reguler, sudah lunas',
                'confirmed_at' => Carbon::now()->subHour(),
                'confirmed_by' => $admin->id,
            ],

            // Future booking - pending
            [
                'user_id' => $user->id,
                'field_id' => $fields->get(3)->id,
                'booking_date' => Carbon::now()->addDays(3),
                'start_time' => '20:00:00',
                'end_time' => '22:00:00',
                'duration_hours' => 2,
                'total_price' => $fields->get(3)->price_per_hour * 2,
                'status' => 'pending',
                'customer_name' => 'Lisa Permata',
                'customer_phone' => '085678901234',
                'customer_email' => 'lisa.permata@email.com',
                'notes' => 'Booking untuk gathering keluarga',
                'admin_notes' => null,
            ],

            // Future booking - 3 hours duration
            [
                'user_id' => $user->id,
                'field_id' => $fields->get(1)->id,
                'booking_date' => Carbon::now()->addDays(5),
                'start_time' => '16:00:00',
                'end_time' => '19:00:00',
                'duration_hours' => 3,
                'total_price' => $fields->get(1)->price_per_hour * 3,
                'status' => 'confirmed',
                'customer_name' => 'PT. Sports Sejahtera',
                'customer_phone' => '086789012345',
                'customer_email' => 'contact@sportssejahtera.com',
                'notes' => 'Event company outing - butuh sound system',
                'admin_notes' => 'Corporate client, sudah DP 50%',
                'confirmed_at' => Carbon::now()->subMinutes(30),
                'confirmed_by' => $admin->id,
            ],

            // Cancelled booking
            [
                'user_id' => $user->id,
                'field_id' => $fields->get(2)->id,
                'booking_date' => Carbon::now()->addDays(7),
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'duration_hours' => 2,
                'total_price' => $fields->get(2)->price_per_hour * 2,
                'status' => 'cancelled',
                'customer_name' => 'Andi Pratama',
                'customer_phone' => '087890123456',
                'customer_email' => 'andi.pratama@email.com',
                'notes' => 'Minta reschedule karena hujan',
                'admin_notes' => 'Dibatalkan karena cuaca buruk',
                'cancelled_at' => Carbon::now()->subMinutes(15),
            ],

            // Weekend premium booking
            [
                'user_id' => $user->id,
                'field_id' => $fields->get(2)->id, // Elite Premium
                'booking_date' => Carbon::now()->next('Saturday'),
                'start_time' => '10:00:00',
                'end_time' => '14:00:00',
                'duration_hours' => 4,
                'total_price' => $fields->get(2)->price_per_hour * 4,
                'status' => 'confirmed',
                'customer_name' => 'Tournament Organizer',
                'customer_phone' => '088901234567',
                'customer_email' => 'tournament@futsal.id',
                'notes' => 'Semi final tournament - butuh live streaming',
                'admin_notes' => 'VIP event, semua fasilitas aktif',
                'confirmed_at' => Carbon::now()->subDays(2),
                'confirmed_by' => $admin->id,
            ],
        ];

        foreach ($bookings as $bookingData) {
            Booking::create($bookingData);
        }
    }
}
