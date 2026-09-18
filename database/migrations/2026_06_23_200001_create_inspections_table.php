<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Create inspections table for vehicle return/inspection by HRD
     */
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrow_request_id')->constrained('borrow_requests')->onDelete('cascade');
            $table->integer('fuel_level')->nullable()->comment('Fuel level in percentage or bars');
            $table->integer('last_km')->nullable()->comment('Last kilometer reading');
            $table->text('physical_condition_notes')->nullable()->comment('Notes about physical condition');
            $table->boolean('is_damaged')->default(false)->comment('Whether vehicle is damaged');
            $table->string('damage_photos')->nullable()->comment('Path to damage photos');
            $table->foreignId('inspected_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
