<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

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

    public function getSizeAttribute()
    {
        $sizeinbytes = Storage::disk('public')->size($this->file_path);
        return Number::fileSize($sizeinbytes);
    }
}
