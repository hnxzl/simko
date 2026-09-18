<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        Schema::table('drivers', function (Blueprint $table) {

            if (!Schema::hasColumn('drivers', 'nama_driver')) {
                $table->string('nama_driver')->default('')->after('id');
            }
            if (!Schema::hasColumn('drivers', 'nik')) {
                $table->string('nik', 30)->nullable()->after('nama_driver');
            }
            if (!Schema::hasColumn('drivers', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('nik');
            }
            if (!Schema::hasColumn('drivers', 'jenis_sim')) {
                $table->string('jenis_sim', 10)->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('drivers', 'alamat')) {
                $table->text('alamat')->nullable()->after('jenis_sim');
            }
            if (!Schema::hasColumn('drivers', 'foto')) {
                $table->string('foto')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('drivers', 'status')) {
                $table->enum('status', ['aktif', 'bertugas', 'nonaktif'])->default('aktif')->after('foto');
            }
        });
    }

    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            if (Schema::hasColumn('drivers', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('drivers', 'foto')) {
                $table->dropColumn('foto');
            }
            if (Schema::hasColumn('drivers', 'alamat')) {
                $table->dropColumn('alamat');
            }
            if (Schema::hasColumn('drivers', 'jenis_sim')) {
                $table->dropColumn('jenis_sim');
            }
            if (Schema::hasColumn('drivers', 'no_hp')) {
                $table->dropColumn('no_hp');
            }
            if (Schema::hasColumn('drivers', 'nik')) {
                $table->dropColumn('nik');
            }
            if (Schema::hasColumn('drivers', 'nama_driver')) {
                $table->dropColumn('nama_driver');
            }
        });
    }
};

