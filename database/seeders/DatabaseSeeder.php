<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Divisi;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        ]);

        $judulDivisi = ['KSBU','KTOKPD','KASI JASA','BLU','SPI','PENGELOLA ANGGARAN'];
        $i = 1;
        foreach ($judulDivisi as $judul) {
            Divisi::create([
                'judul' => $judul
            ]);

            User::factory()->create([
                'name' => $judul.' Admin',
                'email' => Str::slug($judul).'@demo.com',
                'divisi_id' => $i++
            ]);
        }
    }
}
