<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    public function surat()
    {
        return $this->hasMany(SuratKeluar::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function bagian()
    {
        return $this->hasMany(Bagian::class);
    }

    protected function casts(): array
    {
        return [
            'kategori' => 'array',
        ];
    }
}
