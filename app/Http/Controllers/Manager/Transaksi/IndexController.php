<?php

namespace App\Http\Controllers\Manager\Transaksi;

use App\Http\Controllers\Auth\Controller;
use App\Models\Pegawai;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function render()
    {
        $data = Pegawai::with('Transaksi')->get();
        return view('manager.transaksi.index', compact('data'));
    }

    public function renderAll()
    {
        $data = Transaksi::with(['Keranjang' => function($q) {
            $q->with('Produk')->where('status_keranjang', 'transaksi');
        }])->with('Pegawai')->get();

        return $this->resJson(false, ['data' => $data], 200);
    }

    public function renderById($id)
    {
        $data = Transaksi::with(['Keranjang' => function($q) {
            $q->with('Produk')->where('status_keranjang', 'transaksi');
        }])->with('Pegawai')->where('id', $id)->get();

        return $this->resJson(false, ['data' => $data], 200);
    }

    public function searchPegawai(Request $request)
    {
        $data = Transaksi::with(['Keranjang' => function($q) {
            $q->with('Produk')->where('status_keranjang', 'transaksi');
        }])->with('Pegawai')->whereHas('Pegawai', function ($q) use($request) {
            $q->where('nama', 'like', '%' . $request->search . '%');
        })->get();

        return $this->resJson(false, ['data' => $data], 200);
    }

    public function filterTgl(Request $request)
    {
        $data = Transaksi::with(['Keranjang' => function($q) {
            $q->with('Produk')->where('status_keranjang', 'transaksi');
        }])->with('Pegawai')->where('created_at', 'like', '%' . $request->date . '%')->get();

        return $this->resJson(false, ['data' => $data], 200);
    }
}
