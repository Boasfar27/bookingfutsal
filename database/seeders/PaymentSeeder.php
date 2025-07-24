<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get admin user and bookings
        $admin = User::where('email', 'admin@example.com')->first();
        $bookings = Booking::all();

        if (!$admin || $bookings->count() === 0) {
            $this->command->error('Please run RolePermissionSeeder and BookingSeeder first!');
            return;
        }

        $payments = [
            // Verified payment - bank transfer
            [
                'booking_id' => $bookings->first()->id,
                'amount' => $bookings->first()->total_price,
                'payment_method' => 'bank_transfer',
                'proof_file_path' => 'payment-proofs/sample-transfer-proof-1.jpg',
                'proof_file_name' => 'bukti_transfer_bca.jpg',
                'status' => 'verified',
                'payment_notes' => 'Transfer via BCA mobile banking',
                'verification_notes' => 'Transfer confirmed, amount matches',
                'paid_at' => Carbon::yesterday()->addHours(10),
                'verified_at' => Carbon::yesterday()->addHours(12),
                'verified_by' => $admin->id,
                'bank_name' => 'BCA',
                'account_holder_name' => 'Ahmad Fadli',
            ],

            // Pending payment - waiting verification
            [
                'booking_id' => $bookings->get(1)->id,
                'amount' => $bookings->get(1)->total_price,
                'payment_method' => 'bank_transfer',
                'proof_file_path' => 'payment-proofs/sample-transfer-proof-2.jpg',
                'proof_file_name' => 'bukti_transfer_mandiri.jpg',
                'status' => 'pending',
                'payment_notes' => 'Transfer via Mandiri ATM',
                'verification_notes' => null,
                'paid_at' => Carbon::today()->addHours(8),
                'verified_at' => null,
                'verified_by' => null,
                'bank_name' => 'Mandiri',
                'account_holder_name' => 'Sari Indah',
            ],

            // Pending payment - recent
            [
                'booking_id' => $bookings->get(2)->id,
                'amount' => $bookings->get(2)->total_price,
                'payment_method' => 'bank_transfer',
                'proof_file_path' => 'payment-proofs/sample-transfer-proof-3.jpg',
                'proof_file_name' => 'bukti_transfer_bni.jpg',
                'status' => 'pending',
                'payment_notes' => 'Transfer via BNI internet banking, mohon segera dikonfirmasi',
                'verification_notes' => null,
                'paid_at' => Carbon::now()->subHours(2),
                'verified_at' => null,
                'verified_by' => null,
                'bank_name' => 'BNI',
                'account_holder_name' => 'Budi Santoso',
            ],

            // E-wallet payment - verified
            [
                'booking_id' => $bookings->get(3)->id,
                'amount' => $bookings->get(3)->total_price,
                'payment_method' => 'ewallet',
                'proof_file_path' => 'payment-proofs/sample-ewallet-proof-1.jpg',
                'proof_file_name' => 'bukti_gopay.jpg',
                'status' => 'verified',
                'payment_notes' => 'Pembayaran via GoPay',
                'verification_notes' => 'GoPay payment confirmed',
                'paid_at' => Carbon::now()->subHours(4),
                'verified_at' => Carbon::now()->subHours(3),
                'verified_by' => $admin->id,
                'bank_name' => null,
                'account_holder_name' => 'Tim Futsal Garuda',
            ],

            // Rejected payment - wrong amount
            [
                'booking_id' => $bookings->get(4)->id,
                'amount' => $bookings->get(4)->total_price,
                'payment_method' => 'bank_transfer',
                'proof_file_path' => 'payment-proofs/sample-transfer-proof-4.jpg',
                'proof_file_name' => 'bukti_transfer_error.jpg',
                'status' => 'rejected',
                'payment_notes' => 'Transfer via BCA',
                'verification_notes' => 'Amount does not match. Expected: Rp ' . number_format($bookings->get(4)->total_price, 0, ',', '.') . ', but received different amount.',
                'paid_at' => Carbon::now()->subDays(2),
                'verified_at' => null,
                'rejected_at' => Carbon::now()->subDays(2)->addHours(2),
                'verified_by' => $admin->id,
                'bank_name' => 'BCA',
                'account_holder_name' => 'Lisa Permata',
            ],

            // Cash payment - verified
            [
                'booking_id' => $bookings->get(5)->id,
                'amount' => $bookings->get(5)->total_price,
                'payment_method' => 'cash',
                'proof_file_path' => null,
                'proof_file_name' => null,
                'status' => 'verified',
                'payment_notes' => 'Pembayaran tunai langsung di tempat',
                'verification_notes' => 'Cash payment received directly',
                'paid_at' => Carbon::now()->subHours(1),
                'verified_at' => Carbon::now()->subHours(1),
                'verified_by' => $admin->id,
                'bank_name' => null,
                'account_holder_name' => null,
            ],

            // Pending payment - no proof yet
            [
                'booking_id' => $bookings->get(6)->id,
                'amount' => $bookings->get(6)->total_price,
                'payment_method' => 'bank_transfer',
                'proof_file_path' => null,
                'proof_file_name' => null,
                'status' => 'pending',
                'payment_notes' => 'Akan transfer dalam 1 jam',
                'verification_notes' => null,
                'paid_at' => Carbon::now()->addHour(),
                'verified_at' => null,
                'verified_by' => null,
                'bank_name' => 'BCA',
                'account_holder_name' => 'Andi Pratama',
            ],

            // Premium booking payment - verified
            [
                'booking_id' => $bookings->get(7)->id,
                'amount' => $bookings->get(7)->total_price,
                'payment_method' => 'bank_transfer',
                'proof_file_path' => 'payment-proofs/sample-transfer-proof-5.jpg',
                'proof_file_name' => 'bukti_transfer_premium.jpg',
                'status' => 'verified',
                'payment_notes' => 'Transfer untuk booking premium - tournament',
                'verification_notes' => 'Premium payment verified. All facilities confirmed.',
                'paid_at' => Carbon::now()->subDays(3),
                'verified_at' => Carbon::now()->subDays(3)->addMinutes(30),
                'verified_by' => $admin->id,
                'bank_name' => 'Mandiri',
                'account_holder_name' => 'Tournament Organizer',
            ],
        ];

        foreach ($payments as $paymentData) {
            Payment::create($paymentData);
        }

        $this->command->info('Demo payments created successfully!');
        $this->command->info('8 payments created with various statuses:');
        $this->command->info('- 4 verified payments');
        $this->command->info('- 3 pending payments');
        $this->command->info('- 1 rejected payment');
        $this->command->info('Payment methods: Bank Transfer, E-Wallet, Cash');
    }
}
