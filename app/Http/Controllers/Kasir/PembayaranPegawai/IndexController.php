<?php

namespace App\Http\Controllers\Kasir\PembayaranPegawai;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Pegawai;
use App\Models\PembayaranPegawai as Pembayaran;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function renderJson()
    {
        $data = Pembayaran::where('pegawai_id', $this->idPegawai)->first();

        return $this->resJson(false, ['data' => $data], 200);
    }

    public function addPesanModal(Request $request)
    {
        $keranjang = Keranjang::where('id', $request->keranjangId);
        $keranjang->update([
            'jumlah' => $keranjang->first()->jumlah + 1
        ]);
        $getProdukHarga = Produk::where('id', $keranjang->first()->produk_id);
        $totalPembayaran = $getProdukHarga->first()->harga * 1;

        $cekByrKasir = Pembayaran::where('pegawai_id', $this->idPegawai);
        $cekByrKasir->update([
            'total_pembayaran' => $cekByrKasir->first()->total_pembayaran + $totalPembayaran
        ]);
        $this->createLog('Pegawai '.$this->namaPegawai. ' menambahkan produk id ' . $request->produk_id . ' ke keranjang');
        return $this->resJson(false, ['message' => 'Berhasil menambahkan pesanan'], 200);

    }

    public function kurangPesanModal(Request $request)
    {
        $keranjang = Keranjang::where('id', $request->keranjangId);
        if ($keranjang->first()->jumlah == 1) {
            $getProdukHarga = Produk::where('id', $keranjang->first()->produk_id);
            $totalPembayaran = $getProdukHarga->first()->harga * 1;

            $cekByrKasir = Pembayaran::where('pegawai_id',$this->idPegawai);
            $cekByrKasir->update([
                'total_pembayaran' => $cekByrKasir->first()->total_pembayaran - $totalPembayaran
            ]);

            $keranjang->delete();
            $this->createLog('Pegawai '.$this->namaPegawai. ' menghapus produk id ' . $getProdukHarga->id);
            return $this->resJson(false, ['message' => 'Berhasil menghapus pesanan'], 200);
        } else {
            $keranjang->update([
                'jumlah' => $keranjang->first()->jumlah - 1
            ]);
            $getProdukHarga = Produk::where('id', $keranjang->first()->produk_id);
            $totalPembayaran = $getProdukHarga->first()->harga * 1;

            $cekByrKasir = Pembayaran::where('pegawai_id',$this->idPegawai);
            $cekByrKasir->update([
                'total_pembayaran' => $cekByrKasir->first()->total_pembayaran - $totalPembayaran
            ]);
            $this->createLog('Pegawai '.$this->namaPegawai. ' mengurangi produk id ' . $getProdukHarga->id . ' dari keranjang');
            return $this->resJson(false, ['message' => 'Berhasil mengurangi pesanan'], 200);
        }
    }
}
