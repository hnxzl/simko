<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('borrow_requests', 'hrd_notes')) {
                $table->text('hrd_notes')->nullable()->after('manager_notes');
            }
            if (!Schema::hasColumn('borrow_requests', 'bod_notes')) {
                $table->text('bod_notes')->nullable()->after('hrd_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->dropColumn(['hrd_notes', 'bod_notes']);
        });
    }
};
