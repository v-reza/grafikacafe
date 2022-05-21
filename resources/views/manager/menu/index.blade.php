@extends('manager.master.template')
@section('title', 'Menu Manager')
@section('content')
<h3>List Menu</h3>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Tambah Menu
</button>

<!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Menu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('addmenu') }}" method="POST" enctype='multipart/form-data'>
                @csrf
                <div class="mb-3">
                  <label for="nama_produk" class="form-label">Nama Produk</label>
                  <input type="text" name="nama_produk" class="form-control" id="nama_produk" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar</label>
                    <input type="file" name="gambar" class="form-control" id="gambar">
                </div>
                <div class="mb-3">
                  <label for="harga" class="form-label">Harga</label>
                  <input type="text" name="harga" class="form-control" id="harga" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                  <label for="kategori" class="form-label">Kategori</label>
                  <select id="kategori" name="kategori" class="form-select" aria-label="Default select example">
                    <option selected>Pilih kategori</option>
                    <option value="makanan">Makanan</option>
                    <option value="minuman">Minuman</option>
                  </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
      </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content" id="rowEdit">

      </div>
    </div>
</div>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
      <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
  </svg>

  @if (Session::has('sukses'))
  <div class="alert alert-success d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
    <div>
      {{ Session::get('sukses') }}
    </div>
  </div>
  @endif
  @if (Session::has('error'))
  <div class="alert alert-danger d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
    <div>
      {{ Session::get('error') }}
    </div>
  </div>
  @endif
<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nama Produk</th>
        <th scope="col">Gambar</th>
        <th scope="col">Harga</th>
        <th scope="col">Kategori</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $value)
        <tr>
            <td>{{ $index+1 }}</td>
            <td>{{ $value->nama_produk }}</td>
            <td><img src="{{ asset($value->gambar) }}" style="width: 10%px; height: 50px"  class="img-thumbnail"></td>
            <td>Rp. {{ number_format($value->harga) }}</td>
            <td>{{ ucfirst($value->kategori) }}</td>
            <td>
                <button type="button" class="btn btn-warning" data-id="{{ $value->id }}" onclick="updateMenu(this)">Edit</button>
                <button type="button" class="btn btn-danger" data-id="{{ $value->id }}" onclick="deleteMenu(this)">Hapus</button>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
@endsection
@section('script')
  <script>
      function updateMenu(id)
      {
        const dataId = $(id).data("id")
        location.replace('/manager/editmenu/' + dataId);
      }

      function konfirmUpdate(id)
      {
          const dataId = $(id).data("id")
          var file_data = $("#gambar").prop('files')[0]
          var form_data = new FormData()
          form_data.append('file', file_data)
          console.log(form_data)
      }


      function deleteMenu(id)
      {
        const dataId = $(id).data("id")
        swal({
            title: 'Apakah anda yakin menghapus?',
            icon: 'warning'
        }).then(function (ok) {
            if(ok) {
                $.ajax({
                  type: "POST",
                  url: "/manager/delete/" + dataId,
                  data: {
                      "_token": "{{ csrf_token() }}"
                  },
                  success: function (response) {
                    location.replace('/manager/menu')
                  }
                });
            }
        })
      }
  </script>
@endsection
