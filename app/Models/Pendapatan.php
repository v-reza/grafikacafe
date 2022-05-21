<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    use HasFactory;
    protected $fillable = [
        'pegawai_id',
        'total_pendapatan',
        'tanggal'
    ];
}
