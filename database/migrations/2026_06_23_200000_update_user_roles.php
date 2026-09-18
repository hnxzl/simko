<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update user roles from old (admin, manager, ketua tim, pegawai, external)
     * to new (admin, hrd, karyawan, manager, bod)
     */
    public function up(): void
    {
        // First, change the role column to VARCHAR so we can write new values
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'karyawan'");

        // Map old roles to new roles
        DB::statement("UPDATE users SET role = 'hrd' WHERE LOWER(role) = 'ketua tim'");
        DB::statement("UPDATE users SET role = 'karyawan' WHERE LOWER(role) = 'pegawai'");
        DB::statement("UPDATE users SET role = 'bod' WHERE LOWER(role) = 'external'");
        // 'admin' and 'manager' stay the same
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert new roles back to old roles
        DB::statement("UPDATE users SET role = 'ketua tim' WHERE LOWER(role) = 'hrd'");
        DB::statement("UPDATE users SET role = 'pegawai' WHERE LOWER(role) = 'karyawan'");
        DB::statement("UPDATE users SET role = 'external' WHERE LOWER(role) = 'bod'");
    }
};
