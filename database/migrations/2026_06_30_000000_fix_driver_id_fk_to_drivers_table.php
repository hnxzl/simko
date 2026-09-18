<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old FK that wrongly points to users.id
        // Laravel default FK name: {table}_{column}_foreign
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
        });

        // Add correct FK pointing to drivers.id
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->foreign('driver_id')
                ->references('id')
                ->on('drivers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
        });

        // Restore old FK
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->foreign('driver_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }
};
