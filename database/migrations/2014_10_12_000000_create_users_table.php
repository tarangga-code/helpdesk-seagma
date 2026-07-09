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
        // Pastikan ini menggunakan Schema::create, bukan Schema::table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            $table->string('role')->default('pelanggan'); 
            $table->string('no_telepon')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('kecamatan')->nullable(); 
            $table->string('kecamatan_tugas')->nullable();

            // 💡 PASTIKAN DUA BARIS INI ADA DI TABEL USERS:
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};