<div class="hidden" id="manager">
    <br>
    <button class="btn btn-primary" onclick="create('manager')">Tambah Data</button>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Manager</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($manager as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->Manager->nama }}</td>
                    <td><button type="button" class="btn btn-warning" data-id="{{ $item->id }}" onclick="edit(this)">Edit </button>
                        <button type="button" class="btn btn-danger" data-id="{{ $item->id }}" onclick="hapusManager(this)">Hapus </button>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>
