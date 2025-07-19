<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    public function dokumen()
    {
        return $this->hasMany(DokumenMasuk::class);
    }
}
