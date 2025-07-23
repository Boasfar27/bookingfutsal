<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['bank_transfer', 'cash', 'ewallet'])->default('bank_transfer');
            $table->string('proof_file_path')->nullable(); // Path to uploaded proof
            $table->string('proof_file_name')->nullable(); // Original filename
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('payment_notes')->nullable(); // Customer payment notes
            $table->text('verification_notes')->nullable(); // Admin verification notes
            $table->timestamp('paid_at')->nullable(); // When customer claims to have paid
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable(); // Admin who verified
            $table->string('bank_name')->nullable(); // Bank used for transfer
            $table->string('account_holder_name')->nullable(); // Name on bank account
            $table->timestamps();

            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'verified_at']);
            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
