<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah_referensi', function (Blueprint $table) {
            $table->id();
            $table->string('kecamatan', 50);
            $table->string('desa', 50);
            $table->decimal('lat_default', 10, 8)->nullable();
            $table->decimal('lng_default', 11, 8)->nullable();
            $table->unique(['kecamatan', 'desa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah_referensi');
    }
};
