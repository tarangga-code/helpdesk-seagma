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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'no_telepon')) {
                $table->string('no_telepon')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'alamat_lengkap')) {
                $table->text('alamat_lengkap')->nullable()->after('no_telepon');
            }
            if (!Schema::hasColumn('users', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('alamat_lengkap');
            }
            if (!Schema::hasColumn('users', 'kecamatan_tugas')) {
                $table->string('kecamatan_tugas')->nullable()->after('kecamatan');
            }
            if (!Schema::hasColumn('users', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('kecamatan_tugas');
            }
            if (!Schema::hasColumn('users', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('users', 'no_telepon')) $columnsToDrop[] = 'no_telepon';
            if (Schema::hasColumn('users', 'alamat_lengkap')) $columnsToDrop[] = 'alamat_lengkap';
            if (Schema::hasColumn('users', 'kecamatan')) $columnsToDrop[] = 'kecamatan';
            if (Schema::hasColumn('users', 'kecamatan_tugas')) $columnsToDrop[] = 'kecamatan_tugas';
            if (Schema::hasColumn('users', 'latitude')) $columnsToDrop[] = 'latitude';
            if (Schema::hasColumn('users', 'longitude')) $columnsToDrop[] = 'longitude';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
