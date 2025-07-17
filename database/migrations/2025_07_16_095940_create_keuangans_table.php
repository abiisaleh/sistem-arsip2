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
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();
            $table->string('idDokumen')->unique();
            $table->string('judul');
            $table->string('Oleh');
            $table->date('tanggal');
            $table->enum('kategori', ['KSBU','KTOKPD','KASI JASA','BLU','SPI','PENGELOLA ANGGARAN']);
            $table->string('deskripsi');
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangans');
    }
};
