<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $fillable = [
        'user_id', 'buku_id', 'tanggal_pinjam', 'tgl_kembali_rencana',
        'tgl_kembali_aktual', 'status', 'catatan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function denda()
    {
        return $this->hasOne(Denda::class);
    }
}