<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    public function dokumen()
    {
        return $this->hasMany(DokumenKeluar::class);
    }
}
