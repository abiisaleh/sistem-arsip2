<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\User;
use App\Models\Divisi;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use function Pest\Laravel\json;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Pimpinan',
            'email' => 'master@demo.com',
            'role' => 'verifikator'
        ]);

        $departemen = ['BUMN','PT Freeport'];
        foreach ($departemen as $judul)
            Departemen::create(['judul' => $judul]);

        $divisi = [
            "KSBU" => [
                "Pengelola Kepegawaian",
                "Pengelola BMN",
                "Penyusun Rencana dan Program",
                "Pengevaluasi dan Penyusunan Laporan",
                "Tata Usaha",
            ],
            "KTOKTP" => [
                "Koordinator Avsec",
                "Koordinator PKP-PK",
                "Koordinator Bangland",
                "Koordinator Elban",
                "Koordinator Listrik",
                "Koordinator A2B",
                "Tim Slot / Chronos",
            ],
            "KASI JASA" => [
                "Koordinator AMC",
                "Koordinator Informasi",
                "Pas Bandara",
                "Petugas LLAU",
                "SPI",
                "Sekretaris",
            ]
        ];
        $i = 1;
        foreach ($divisi as $judul => $bagian) {
            Divisi::create([
                'judul' => $judul,
                'bagian' => $bagian
            ]);

            User::factory()->create([
                'name' => $judul.' user',
                'email' => Str::slug($judul).'@demo.com',
                'role' => 'user',
                'divisi_id' => $i++
            ]);
        }
    }
}
