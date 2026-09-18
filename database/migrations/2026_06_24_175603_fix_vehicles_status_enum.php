<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, change to VARCHAR to allow new values
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'available'");

        // Map old values to new values
        DB::statement("UPDATE vehicles SET status = 'in_use' WHERE status = 'is_use'");
        DB::statement("UPDATE vehicles SET status = 'maintenance' WHERE status = 'unavailable'");

        // Change to new ENUM
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN status ENUM('available', 'in_use', 'maintenance') NOT NULL DEFAULT 'available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE vehicles SET status = 'is_use' WHERE status = 'in_use'");
        DB::statement("UPDATE vehicles SET status = 'unavailable' WHERE status = 'maintenance'");
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN status ENUM('available', 'is_use', 'unavailable') NOT NULL DEFAULT 'available'");
    }
};
