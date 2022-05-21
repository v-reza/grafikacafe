<?php

namespace App\Http\Controllers\Manager\Pendapatan;

use App\Http\Controllers\Auth\Controller;
use App\Models\Pendapatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexControler extends Controller
{
    public function render()
    {
        return view('manager.pendapatan.index');
    }

    public function renderJson()
    {
        $data = Pendapatan::all();
        $total = [];
        foreach ($data as $pendapatan) {
            $totalPendapatan = $pendapatan->total_pendapatan;
            array_push($total, $totalPendapatan);
        }
        return $this->resJson(false, ['data' => array_sum($total)], 200);
    }

    public function filterHarian(Request $request)
    {
        $data = Pendapatan::where('tanggal', 'like', '%' . $request->date . '%')->get();
        $total = [];
        foreach ($data as $pendapatan) {
            $totalPendapatan = $pendapatan->total_pendapatan;
            array_push($total, $totalPendapatan);
        }
        return $this->resJson(false, ['data' => array_sum($total)], 200);
    }

    public function filterBulanan(Request $request)
    {
        $data = Pendapatan::whereBetween('tanggal', [$request->tglDari, $request->tglSampai])->get();
        $total = [];
        foreach ($data as $pendapatan) {
            $totalPendapatan = $pendapatan->total_pendapatan;
            array_push($total, $totalPendapatan);
        }
        return $this->resJson(false, ['data' => array_sum($total)], 200);
    }
}
