<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class arsip extends Model
{
    //
    protected $fillable = ['idDokumen','judul','kategori','tanggal','pengirim','deskripsi','file'];
}
