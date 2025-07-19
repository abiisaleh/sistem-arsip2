<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Divisi;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        foreach ($judulDivisi as $judul) {
            Divisi::create([
                'judul' => $judul
            ]);
        }
    }
}
