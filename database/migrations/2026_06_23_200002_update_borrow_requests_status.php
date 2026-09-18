<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update borrow_requests status enum and add approval fields
     */
    public function up(): void
    {
        // First, change status column to VARCHAR so we can write new values
        DB::statement("ALTER TABLE borrow_requests MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'pending_manager'");

        // Map old statuses to new statuses
        DB::statement("UPDATE borrow_requests SET status = 'pending_manager' WHERE LOWER(status) = 'pending'");
        DB::statement("UPDATE borrow_requests SET status = 'active' WHERE LOWER(status) = 'in use'");
        DB::statement("UPDATE borrow_requests SET status = 'rejected' WHERE LOWER(status) = 'cancelled'");
        // 'approved', 'completed', 'rejected' stay the same
        
        // Add approval columns if they don't exist
        if (!Schema::hasColumn('borrow_requests', 'manager_approval')) {
            DB::statement("ALTER TABLE borrow_requests ADD COLUMN manager_approval TINYINT(1) DEFAULT 0 AFTER status");
        }
        if (!Schema::hasColumn('borrow_requests', 'bod_approval')) {
            DB::statement("ALTER TABLE borrow_requests ADD COLUMN bod_approval TINYINT(1) DEFAULT 0 AFTER manager_approval");
        }
        if (!Schema::hasColumn('borrow_requests', 'manager_notes')) {
            DB::statement("ALTER TABLE borrow_requests ADD COLUMN manager_notes TEXT NULL AFTER bod_approval");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert new statuses back to old statuses
        DB::statement("UPDATE borrow_requests SET status = 'pending' WHERE status = 'pending_manager'");
        DB::statement("UPDATE borrow_requests SET status = 'pending' WHERE status = 'pending_hrd'");
        DB::statement("UPDATE borrow_requests SET status = 'approved' WHERE status = 'approved'");
        DB::statement("UPDATE borrow_requests SET status = 'in use' WHERE status = 'active'");
        DB::statement("UPDATE borrow_requests SET status = 'completed' WHERE status = 'completed'");
        DB::statement("UPDATE borrow_requests SET status = 'rejected' WHERE status = 'rejected'");
        
        // Remove approval columns
        if (Schema::hasColumn('borrow_requests', 'manager_notes')) {
            DB::statement("ALTER TABLE borrow_requests DROP COLUMN manager_notes");
        }
        if (Schema::hasColumn('borrow_requests', 'bod_approval')) {
            DB::statement("ALTER TABLE borrow_requests DROP COLUMN bod_approval");
        }
        if (Schema::hasColumn('borrow_requests', 'manager_approval')) {
            DB::statement("ALTER TABLE borrow_requests DROP COLUMN manager_approval");
        }
    }
};
