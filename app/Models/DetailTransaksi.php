<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    public function transaksi(){
        return $this->belongsTo(Transaksi::class);
    }

    public function produk(){
        return $this->belongsTo(Produk::class);
    }
}
