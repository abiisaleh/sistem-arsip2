<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    public function surat()
    {
        return $this->hasMany(SuratMasuk::class);
    }
}
