<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'harga_beli',
        'harga_jual',
        'stok',
        'deskripsi',
    ];
    public function kategori(){
        return $this->belongsTo(Kategori::class);
    }

    public function detailTransaksi(){
        return $this->hasMany(DetailTransaksi::class);
    }
}
