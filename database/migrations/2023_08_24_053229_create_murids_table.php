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
        Schema::create('murids', function (Blueprint $table) {
            $table->id();
            $table->integer('rayon_id');
            $table->integer('thn_id');
            $table->integer('user_id');
            $table->string('mrd_id');
            $table->string('nik');
            $table->string('nama');
            $table->string('jns_klmin');
            $table->string('email');
            $table->string('foto');
            $table->enum('tingkat', ['dasar','badge', 'putih', 'kuning', 'merah', 'biru', 'coklat', 'hitam']);
            $table->string('alamat');
            $table->string('tmpt');
            $table->string('tgl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('murids');
    }
};
