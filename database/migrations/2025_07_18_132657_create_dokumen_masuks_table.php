<?php

use App\Models\User;
use App\Models\Departemen;
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
            $table->bool('is_private')->default(true);
            $table->string('file');
            $table->boolean('is_private')->default(false);
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Departemen::class)->nullable()->constrained()->nullOnDelete();
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
