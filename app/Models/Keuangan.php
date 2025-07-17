<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $fillable = ['idDokumen','judul','kategori','tanggal','Oleh','deskripsi','file'];
}
