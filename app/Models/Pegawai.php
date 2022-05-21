<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama'
    ];

    /**
     * Get all of the Transaksi for the Pegawai
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Transaksi()
    {
        return $this->hasMany(Transaksi::class, 'pegawai_id', 'id');
    }
}
