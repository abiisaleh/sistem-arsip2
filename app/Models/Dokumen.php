<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function getIconAttribute()
    {
        $ext = pathinfo($this->file_name, PATHINFO_EXTENSION);
        return 'bi-filetype-' . $ext;
    }
}
