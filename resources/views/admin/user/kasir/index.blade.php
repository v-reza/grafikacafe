<div class="hidden" id="kasir">
    <br>
    <button class="btn btn-primary" onclick="create('kasir')">Tambah Data</button>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Pegawai</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->Pegawai->nama }}</td>
                    <td><button type="button" class="btn btn-warning" data-id="{{ $item->id }}" onclick="edit(this)">Edit </button>
                        <button type="button" class="btn btn-danger" data-id="{{ $item->id }}" onclick="hapusKasir(this)">Hapus </button>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>
