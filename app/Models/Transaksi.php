<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'karyawan_id', 'kode_transaksi', 'tanggal_transaksi',
    'total_harga', 'total_modal', 'keterangan',
    ];
    public function karyawan(){
        return $this->belongsTo(Karyawan::class);
    }

    public function detailTransaksis(){
        return $this->hasMany(DetailTransaksi::class);
    }
}
