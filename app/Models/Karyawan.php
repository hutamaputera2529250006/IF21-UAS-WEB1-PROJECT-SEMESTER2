<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $fillable = [
        'nama_karyawan',
        'jabatan',
        'no_telepon',
        'alamat',
        'tanggal_masuk',
    ];
    public function transaksis(){
        return $this->hasMany(Transaksi::class);
    }
}
