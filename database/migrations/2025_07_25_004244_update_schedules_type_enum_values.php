<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to alter the ENUM column
        DB::statement("ALTER TABLE schedules MODIFY COLUMN type ENUM('blocked', 'maintenance', 'event', 'private', 'cleaning', 'renovation') DEFAULT 'blocked'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM values
        DB::statement("ALTER TABLE schedules MODIFY COLUMN type ENUM('blocked', 'maintenance', 'event', 'private') DEFAULT 'blocked'");
    }
};