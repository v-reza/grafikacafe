<?php

namespace App\Http\Controllers\Kasir\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Models\PembayaranPegawai as Pembayaran;
use App\Models\Meja;
use App\Models\Pendapatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function renderJson()
    {
        $riwayatTransaksi = Transaksi::with(['Keranjang' => function($q) {
            $q->with('Produk')->where('status_keranjang', 'transaksi');
        }])->where('pegawai_id', $this->idPegawai)->get();

        return $this->resJson(false, ['data' => $riwayatTransaksi], 200);
    }

    public function addTransaksi(Request $request)
    {
        $pembayaranKeranjang = Pembayaran::where('pegawai_id', $this->idPegawai);

        if ($request->tunai < $pembayaranKeranjang->first()->total_pembayaran) {
            return $this->resJson(true, 'Uang tunai tidak cukup untuk melanjutkan pembayaran', 404);
        }

        $transaksi = Transaksi::create([
            'pegawai_id' => $this->idPegawai,
            'total_pembayaran' => $pembayaranKeranjang->first()->total_pembayaran,
            'meja_id' => $request->meja
        ]);

        $updateTransaksiKeranjang = Keranjang::where('status_keranjang', 'menunggu')->where('pegawai_id', $this->idPegawai);

        $pembayaranKeranjang->update([
            'total_pembayaran' => 0
        ]);

        Meja::where('meja_no', $request->meja)->update([
            'status' => 'dipakai'
        ]);

        $getStockKeranjang = $updateTransaksiKeranjang->get();

        foreach($getStockKeranjang as $itemKeranjang) {
            $updateTransaksiKeranjang->update([
                'transaksi_id' => $transaksi->id,
                'status_keranjang' => 'transaksi'
            ]);
        }
        Pendapatan::create([
            'pegawai_id' => $this->idPegawai,
            'total_pendapatan' => $transaksi->total_pembayaran,
            'tanggal' => Carbon::now('year')
        ]);

        $this->createLog('Pegawai '.$this->namaPegawai. ' melakukan transaksi dgn total pembayaran ' . $transaksi->total_pembayaran);
        return $this->resJson(false, ['message' => 'Berhasil melakukan pembayaran'], 200);
    }
}
