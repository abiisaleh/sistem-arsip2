<?php

use App\Models\Bagian;
use App\Models\SuratMasuk;
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
        Schema::create('surat_masuk_bagian', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SuratMasuk::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Bagian::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk_bagian');
    }
};
