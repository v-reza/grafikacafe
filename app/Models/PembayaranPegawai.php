<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPegawai extends Model
{
    use HasFactory;
    protected $fillable = [
        'pegawai_id',
        'total_pembayaran'
    ];
}
