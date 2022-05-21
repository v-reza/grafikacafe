<?php

namespace App\Http\Controllers\Kasir\Produk;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\Helper;
use App\Models\Keranjang;
use App\Models\Pegawai;
use App\Models\PembayaranPegawai;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class IndexController extends Controller
{
    public function renderJson()
    {
        $produk = Produk::all();

        return $this->resJson(false, ['data' => $produk], 200);
    }

    public function addToCart(Request $request)
    {
        $cekKeranjang = Keranjang::where('produk_id', $request->produk_id)
            ->where('pegawai_id', $this->idPegawai)->where('status_keranjang', 'menunggu');

        if (!is_null($cekKeranjang->first())) {
            $cekKeranjang->update([
                'jumlah' => $cekKeranjang->first()->jumlah + 1
            ]);

            $getProduk = Produk::find($cekKeranjang->first()->produk_id);
            $totalPembayaran = $getProduk->harga * 1;
        } else {
            $keranjang = Keranjang::create([
                'pegawai_id' => $this->idPegawai,
                'produk_id' => $request->produk_id,
                'jumlah' => 1,
                'status_keranjang' => 'menunggu'
            ]);

            $getProduk = Produk::find($keranjang->produk_id);
            $totalPembayaran = $getProduk->harga * 1;
        }

        $cekByrKasir = PembayaranPegawai::where('pegawai_id', $this->idPegawai);
        if(is_null($cekByrKasir->first())) {
            PembayaranPegawai::create([
                'pegawai_id' => $this->idPegawai,
                'total_pembayaran' => $totalPembayaran
            ]);
        } else {
            $cekByrKasir->update([
                'total_pembayaran' => $cekByrKasir->first()->total_pembayaran + $totalPembayaran
            ]);
        }

        $this->createLog('Pegawai '.$this->namaPegawai. ' menambahkan produk id ' . $request->produk_id . ' ke keranjang');

    }

    public function deleteCart(Request $request)
    {
        $cekKeranjang = Keranjang::where('id', $request->keranjang_id)
            ->where('pegawai_id', $this->idPegawai);

        $getProduk = Produk::find($cekKeranjang->first()->produk_id);
        $totalPembayaran = $getProduk->harga * $cekKeranjang->first()->jumlah;

        $cekByrKasir = PembayaranPegawai::where('pegawai_id', $this->idPegawai);
        if (!is_null($cekByrKasir->first())) {
            $cekByrKasir->update([
                'total_pembayaran' => $cekByrKasir->first()->total_pembayaran - $totalPembayaran
            ]);
        }
        $this->createLog('Pegawai '.$this->namaPegawai. ' menghapus keranjang id ' . $request->keranjang_id);

        $cekKeranjang->delete();
        return $this->resJson(false, ['message' => 'Berhasil hapus pesanan'], 200);
    }

    public function filterKategori($key)
    {
        $filter = Produk::where('kategori', $key)->get();
        return $this->resJson(false, ['data' => $filter], 200);
    }
}
