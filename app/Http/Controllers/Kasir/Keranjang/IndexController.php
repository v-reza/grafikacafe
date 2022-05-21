<?php

namespace App\Http\Controllers\Kasir\Keranjang;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function renderJson()
    {
        $data = Keranjang::with('Produk')->where('pegawai_id',$this->idPegawai)->where('status_keranjang', 'menunggu');
        return $this->resJson(false, ['total' => $data->count(), 'keranjang' => $data->get()], 200);
    }
}
