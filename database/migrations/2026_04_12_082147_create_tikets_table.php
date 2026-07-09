<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('status')->default('menunggu verifikasi');
            
            // 1. Koordinat lokasi PELANGGAN di tiket
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // 2. Koordinat lokasi TEKNISI saat upload bukti
            $table->decimal('latitude_teknisi', 10, 8)->nullable();
            $table->decimal('longitude_teknisi', 11, 8)->nullable();
            
            $table->string('foto_bukti')->nullable(); // Wadah untuk nama file foto
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};