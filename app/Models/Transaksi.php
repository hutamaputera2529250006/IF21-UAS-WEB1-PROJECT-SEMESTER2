<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    public function karyawan(){
        return $this->belongsTo(Karyawan::class);
    }

    public function detailTransaksis(){
        return $this->hasMany(DetailTransaksi::class);
    }
}
