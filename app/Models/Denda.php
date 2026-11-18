<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    protected $fillable = [
        'peminjaman_id', 'jumlah_hari_telat', 'nominal_denda', 'status_bayar',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}