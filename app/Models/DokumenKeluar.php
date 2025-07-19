<?php

namespace App\Models;

use App\Models\User;
use App\Models\Divisi;
use Illuminate\Database\Eloquent\Model;

class DokumenKeluar extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
}
