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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['indoor', 'outdoor'])->default('outdoor');
            $table->string('location')->nullable();
            $table->decimal('price_per_hour', 10, 2);
            $table->json('images')->nullable(); // Store multiple images
            $table->json('facilities')->nullable(); // Store facilities list
            $table->time('open_time')->default('06:00:00');
            $table->time('close_time')->default('23:00:00');
            $table->json('operating_days')->nullable(); // Days of week [1,2,3,4,5,6,7]
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('admin_id')->nullable(); // Owner/Admin of this field
            $table->text('payment_info')->nullable(); // Bank account info
            $table->integer('max_hours_per_booking')->default(3);
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['is_active', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
