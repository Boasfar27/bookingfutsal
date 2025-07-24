<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Field;
use App\Models\User;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get admin user
        $admin = User::where('email', 'admin@example.com')->first();

        $fields = [
            [
                'name' => 'Lapangan Futsal Arena A',
                'description' => 'Lapangan futsal indoor dengan rumput sintetis berkualitas tinggi. Dilengkapi dengan pencahayaan LED dan AC. Cocok untuk pertandingan profesional maupun rekreasi.',
                'type' => 'indoor',
                'location' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'price_per_hour' => 150000,
                'images' => [
                    'fields/arena-a-1.jpg',
                    'fields/arena-a-2.jpg',
                    'fields/arena-a-3.jpg'
                ],
                'facilities' => [
                    'AC',
                    'Pencahayaan LED',
                    'Rumput Sintetis Premium',
                    'Sound System',
                    'Ruang Ganti',
                    'Toilet',
                    'Parkir Luas',
                    'Kantin'
                ],
                'open_time' => '06:00:00',
                'close_time' => '23:00:00',
                'operating_days' => [1, 2, 3, 4, 5, 6, 7], // All days
                'is_active' => true,
                'admin_id' => $admin->id,
                'payment_info' => 'Bank BCA: 1234567890 a.n. Futsal Arena A',
                'max_hours_per_booking' => 3,
            ],
            [
                'name' => 'Lapangan Futsal Outdoor B',
                'description' => 'Lapangan futsal outdoor dengan pemandangan yang asri. Udara segar dan natural lighting. Perfect untuk morning workout dan evening games.',
                'type' => 'outdoor',
                'location' => 'Jl. Taman Sari No. 45, Jakarta Barat',
                'price_per_hour' => 100000,
                'images' => [
                    'fields/outdoor-b-1.jpg',
                    'fields/outdoor-b-2.jpg'
                ],
                'facilities' => [
                    'Rumput Sintetis',
                    'Pencahayaan Outdoor',
                    'Ruang Ganti',
                    'Toilet',
                    'Parkir',
                    'Tribun Penonton'
                ],
                'open_time' => '05:00:00',
                'close_time' => '22:00:00',
                'operating_days' => [1, 2, 3, 4, 5, 6, 7],
                'is_active' => true,
                'admin_id' => $admin->id,
                'payment_info' => 'Bank Mandiri: 9876543210 a.n. Outdoor Sports B',
                'max_hours_per_booking' => 4,
            ],
            [
                'name' => 'Elite Futsal Premium C',
                'description' => 'Lapangan futsal premium dengan standar FIFA. Tersedia live streaming dan instant replay. Tempat favorit untuk tournament dan event khusus.',
                'type' => 'indoor',
                'location' => 'Jl. Kemang Raya No. 78, Jakarta Selatan',
                'price_per_hour' => 250000,
                'images' => [
                    'fields/elite-c-1.jpg',
                    'fields/elite-c-2.jpg',
                    'fields/elite-c-3.jpg',
                    'fields/elite-c-4.jpg'
                ],
                'facilities' => [
                    'AC Premium',
                    'Pencahayaan LED Professional',
                    'Rumput Sintetis FIFA Standard',
                    'Sound System Pro',
                    'Live Streaming Setup',
                    'Instant Replay Screen',
                    'VIP Ruang Ganti',
                    'Toilet Premium',
                    'Valet Parking',
                    'Cafe & Restaurant',
                    'Lounge Area'
                ],
                'open_time' => '07:00:00',
                'close_time' => '24:00:00',
                'operating_days' => [1, 2, 3, 4, 5, 6, 7],
                'is_active' => true,
                'admin_id' => $admin->id,
                'payment_info' => 'Bank BNI: 5555666777 a.n. Elite Futsal Premium',
                'max_hours_per_booking' => 5,
            ],
            [
                'name' => 'Community Futsal D',
                'description' => 'Lapangan futsal community dengan harga terjangkau. Cocok untuk bermain santai bersama teman dan keluarga. Suasana friendly dan welcoming.',
                'type' => 'outdoor',
                'location' => 'Jl. Kelapa Gading No. 99, Jakarta Utara',
                'price_per_hour' => 80000,
                'images' => [
                    'fields/community-d-1.jpg',
                    'fields/community-d-2.jpg'
                ],
                'facilities' => [
                    'Rumput Sintetis',
                    'Pencahayaan Standar',
                    'Ruang Ganti',
                    'Toilet',
                    'Parkir',
                    'Warung Makan',
                    'Mushola'
                ],
                'open_time' => '06:00:00',
                'close_time' => '21:00:00',
                'operating_days' => [1, 2, 3, 4, 5, 6, 7],
                'is_active' => true,
                'admin_id' => $admin->id,
                'payment_info' => 'Bank BTN: 1111222333 a.n. Community Sports',
                'max_hours_per_booking' => 2,
            ],
        ];

        foreach ($fields as $fieldData) {
            Field::create($fieldData);
        }
    }
}
