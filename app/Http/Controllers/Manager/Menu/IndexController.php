<?php

namespace App\Http\Controllers\Manager\Menu;

use App\Http\Controllers\Auth\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFile;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    public function render()
    {
        $data = Produk::all();
        return view('manager.menu.index', compact('data'));
    }

    public function addMenu(Request $request, RequestFile $file)
    {
        $input = Validator::make($request->all(), [
            'gambar' => 'required|file'
        ]);
        if ($input->fails()) {
            return back()->with('error', 'Gambar Produk harus ada');
        }

        $fileResume = $file::file('gambar');

        $fileNameResume = $this->generateId() . '.' . $fileResume->getClientOriginalExtension();
        $path = public_path('/menu/');

        $fullPathResume = 'menu/' . $fileNameResume;
        /**
         * Cek ketika path tidak ada maka akan create otomatis
         */
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'gambar' => $fullPathResume,
            'harga' => $request->harga,
            'kategori' => $request->kategori
        ]);

        $fileResume->move($path, $fileNameResume);


        return back()->with('sukses', 'Berhasil menambahkan menu');
    }

    public function getById($id)
    {
        $data = Produk::find($id);
        return $this->resJson(false, ['data' => $data], 200);
    }

    public function editMenu(Request $request, $id, RequestFile $file)
    {
        $input = Validator::make($request->all(), [
            'gambar' => 'required|file'
        ]);
        if ($input->fails()) {
            return back()->with('error', 'Gambar Produk harus ada');
        }

        $fileResume = $file::file('gambar');

        $fileNameResume = $this->generateId() . '.' . $fileResume->getClientOriginalExtension();
        $path = public_path('/menu/');

        $fullPathResume = 'menu/' . $fileNameResume;
        /**
         * Cek ketika path tidak ada maka akan create otomatis
         */
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        Produk::where('id', $id)->update([
            'nama_produk' => $request->nama_produk,
            'gambar' => $fullPathResume,
            'harga' => $request->harga,
            'kategori' => $request->kategori
        ]);
    }

    public function delete($id)
    {
        Produk::where('id', $id)->delete();
        return $this->resJson(false, ['message' => 'Berhasil hapus data'], 200);
    }

    public function renderEdit($id)
    {
        $data = Produk::find($id);
        return view('manager.menu.edit', compact('data'));
    }
}
