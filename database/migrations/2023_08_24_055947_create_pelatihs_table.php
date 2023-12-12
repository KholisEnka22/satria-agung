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
        Schema::create('pelatihs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('mrd_id');
            $table->string('nama_pelatih');
            $table->string('jns_klmin');
            $table->string('nik');
            $table->string('email');
            $table->enum('tingkat', ['badge', 'putih', 'kuning', 'merah', 'biru', 'coklat', 'hitam']);
            $table->string('alamat');
            $table->string('tmpt');
            $table->string('tgl'); // Menggunakan tipe data 'date' untuk tanggal
            $table->string('rayon_id');
            $table->string('foto');
            $table->timestamps();
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihs');
    }
};
