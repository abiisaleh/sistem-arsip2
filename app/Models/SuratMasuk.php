<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    public function divisi()
    {
    return $this->belongsToMany(Divisi::class,'dokumen_masuk_divisi');
    }
}
