<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = [
        'judul', 'penulis', 'penerbit', 'tahun_terbit', 'isbn',
        'kategori_id', 'stok', 'deskripsi', 'cover',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}