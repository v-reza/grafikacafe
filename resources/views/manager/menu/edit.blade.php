@extends('manager.master.template')
@section('content')
<form action="{{ route('editmenu', $data->id) }}" method="POST" enctype='multipart/form-data'>
    @csrf
    <div class="mb-3">
      <label for="nama_produk" class="form-label" >Nama Produk</label>
      <input type="text" name="nama_produk" value="{{ $data->nama_produk }}" class="form-control" id="nama_produk" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
        <label for="gambar" class="form-label">Gambar</label>
        <input type="file" name="gambar" class="form-control" id="gambar">
    </div>
    <div class="mb-3">
      <label for="harga" class="form-label">Harga</label>
      <input type="text" name="harga" value="{{ $data->harga }}" class="form-control" id="harga" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
      <label for="kategori" class="form-label">Kategori</label>
      <select id="kategori" name="kategori" class="form-select" aria-label="Default select example">
        <option selected>Pilih kategori</option>
        <option value="makanan">Makanan</option>
        <option value="minuman">Minuman</option>
      </select>
    </div>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-warning">Update</button>
</div>
</form>
@endsection
