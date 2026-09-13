<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendukung', function (Blueprint $table) {
            $table->id();
            $table->enum('jalur', ['DPC', 'DPRT', 'PIP', 'KIP', 'RELAWAN'])->index();
            $table->string('nik', 20)->index();
            $table->string('nama', 150)->index();
            $table->string('hp', 25)->nullable();
            $table->integer('umur')->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->string('koordinator', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kecamatan', 50)->nullable()->index();
            $table->string('desa', 50)->nullable()->index();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('foto_wajah')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->json('data_khusus')->nullable();
            $table->enum('status', ['Diinput', 'Diverifikasi Desa', 'Divalidasi Kecamatan', 'Final', 'Ditolak'])->default('Diinput')->index();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('input_by_user_id')->nullable();
            $table->string('input_by_user_name', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendukung');
    }
};
