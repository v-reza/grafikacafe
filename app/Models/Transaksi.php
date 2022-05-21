<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'total_pembayaran',
        'meja_id'
    ];

    /**
     * Get all of the Keranjang for the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Keranjang()
    {
        return $this->hasMany(Keranjang::class, 'transaksi_id', 'id');
    }

    /**
     * Get the Pegawai associated with the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id', 'pegawai_id');
    }
}
