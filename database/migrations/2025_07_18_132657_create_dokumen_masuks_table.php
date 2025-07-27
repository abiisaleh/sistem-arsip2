<?php

use App\Models\User;
use App\Models\Departemen;
use App\Models\Divisi;
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
        Schema::create('dokumen_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->string('judul');
            $table->date('tanggal');
            $table->string('deskripsi');
            $table->string('file');
            $table->boolean('is_private')->default(false);
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();

            $table->foreignIdFor(Departemen::class)->nullable()->constrained()->nullOnDelete();
            $table->string('sifat');
            $table->string('isi_disposisi')->nullable()->default(null);
            $table->string('catatan_disposisi')->nullable()->default(null);
            $table->dateTime('verified_at')->nullable()->default(null);
            $table->string('file_disposisi')->nullable()->default(null);

            $table->dateTime('archive_at')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_masuks');
    }
};
