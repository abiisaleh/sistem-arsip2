<?php

namespace App\Models;

use App\Models\User;
use App\Models\Departemen;
use Illuminate\Database\Eloquent\Model;

class DokumenMasuk extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }
}
