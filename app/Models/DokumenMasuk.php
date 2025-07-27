<?php

namespace App\Models;

use App\Models\User;
use App\Models\Departemen;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DokumenMasuk extends Model
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
