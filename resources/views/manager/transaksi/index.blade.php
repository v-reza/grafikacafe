@extends('manager.master.template')
@section('title', 'Manager Transaksi')
@section('customcss')
    <style>
        .transaksi {
            display: none;
        }

    </style>
@endsection
@section('content')
    <h3>List Transaksi Pegawai</h3>
    <button class="btn btn-primary" id="btnTransaksi" onclick="renderAllTransaksi()">Lihat Seluruh Transaksi</button>
    <br><br>
    <div class="transaksi" id="filter">
        <div class="row">
            <h5>Filter</h5>
            <div class="col-md-4">
                <label for="searchTransaksi">Search Nama Pegawai</label>
                <input type="search" class="form-control" id="searchTransaksi" placeholder="Search">
            </div>
            <div class="col-md-4">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" class="form-control">
            </div>
        </div>
    </div>



    @include('manager.transaksi.allTransaksi.index')
    <table class="table" id="byPegawai">
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
                    <td>{{ $item->nama }}</td>
                    <td><button type="button" class="btn btn-success">Lihat Transaksi </button></td>
                </tr>
            @endforeach

        </tbody>
    </table>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $("#searchTransaksi").keyup(function() {
                let html = ''
                const keyword = $("#searchTransaksi").val()
                if (keyword.length == 0) {
                    renderAllTransaksi()
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/manager/searchPegawai",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "search": keyword
                        },
                        success: function(response) {
                            var data = response.data
                            if (data.length == 0 ) {
                                html += `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Sepertinya tidak ada data...
                                        </button>`
                            } else {
                                $.each(data, function(i) {
                                    var date = new Date(data[i].created_at)
                                    var month = date.getMonth() + 1
                                    var fullDate = date.getFullYear() + '-' + month + '-' + date.getDay()
                                    html += `
                                    <tr>
                                        <td>${i+1}</td>
                                        <td>${data[i].id}</td>
                                        <td>${data[i].meja_id}</td>
                                        <td>${data[i].pegawai.nama}</td>
                                        <td>Rp. ${formatToNumber(data[i].total_pembayaran)}</td>
                                        <td>${fullDate}</td>
                                        <td><button type="button" class="btn btn-success" data-id="${data[i].id}" onclick="lihatTransaksi(this)">Lihat Transaksi </button></td>
                                    </tr>
                                    `
                                })
                            }
                            $("#bodyAllTransaksi").html(html)
                        }
                    });
                }

            })

            $("#tanggal").on('change',function() {
                const dateVal = $("#tanggal").val()
                let html = ''
                if (dateVal == "") {
                    renderAllTransaksi()
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/manager/filterTgl",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "date": dateVal
                        },
                        success: function (response) {
                            var data = response.data

                            if (data.length == 0 ) {
                                html += `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Sepertinya tidak ada data...
                                        </button>`
                            } else {
                                $.each(data, function(i) {
                                    var date = new Date(data[i].created_at)
                                    var month = date.getMonth() + 1
                                    var fullDate = date.getFullYear() + '-' + month + '-' + date.getDay()
                                    html += `
                                    <tr>
                                        <td>${i+1}</td>
                                        <td>${data[i].id}</td>
                                        <td>${data[i].meja_id}</td>
                                        <td>${data[i].pegawai.nama}</td>
                                        <td>Rp. ${formatToNumber(data[i].total_pembayaran)}</td>
                                        <td>${fullDate}</td>
                                        <td><button type="button" class="btn btn-success" data-id="${data[i].id}" onclick="lihatTransaksi(this)">Lihat Transaksi </button></td>
                                    </tr>
                                    `
                                })
                            }
                            $("#bodyAllTransaksi").html(html)
                        }
                    });
                }
            })
        })

        function renderAllTransaksi() {
            $('#byPegawai').hide()
            $('#allTransaksi').removeClass("transaksi")
            $('#btnTransaksi').removeClass('btn-primary')
            $('#btnTransaksi').addClass('btn-info')
            $('#btnTransaksi').text('Lihat Seluruh Pegawai')
            $('#btnTransaksi').attr('onclick', 'renderTransaksiPegawai()')
            $('#filter').removeClass('transaksi')
            let html = ''
            $.ajax({
                type: "GET",
                url: "/manager/transaksi/all",
                success: function(response) {
                    var data = response.data
                    $.each(data, function(i) {
                        var date = new Date(data[i].created_at)
                        var month = date.getMonth() + 1
                        var fullDate = date.getFullYear() + '-' + month + '-' + date.getDay()
                        html += `
                        <tr>
                            <td>${i+1}</td>
                            <td>${data[i].id}</td>
                            <td>${data[i].meja_id}</td>
                            <td>${data[i].pegawai.nama}</td>
                            <td>Rp. ${formatToNumber(data[i].total_pembayaran)}</td>
                            <td>${fullDate}</td>
                            <td><button type="button" class="btn btn-success" data-id="${data[i].id}" onclick="lihatTransaksi(this)">Lihat Transaksi </button></td>
                        </tr>
                        `
                    })
                    $("#bodyAllTransaksi").html(html)
                }
            });
        }

        function lihatTransaksi(id) {
            const dataId = $(id).data("id")
            let html = ''
            $.ajax({
                type: "GET",
                url: "/manager/renderTransaksi/" + dataId,
                success: function(response) {
                    var data = response.data
                    $("#transaksiById").modal('toggle')
                    $.each(data, function(i, transaksi) {
                        $.each(transaksi.keranjang, function(k, keranjang) {
                            const totalHarga = data[i].keranjang[k].produk.harga * data[i]
                                .keranjang[k].jumlah
                            html += `
                            <div class="col-md-2">
                                <img class="img-thumbnail" src="/${data[i].keranjang[k].produk.gambar}" alt="">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <span class="text-left">Nama: ${data[i].keranjang[k].produk.nama_produk}</span>
                                    <span class="text-left">Jumlah: ${data[i].keranjang[k].jumlah}</span>
                                    <span class="text-left">Harga / Satuan: Rp. ${formatToNumber(data[i].keranjang[k].produk.harga)}</span>
                                    <span class="text-left">Total Harga: Rp. ${formatToNumber(totalHarga)}</span>
                                </div>
                            </div>
                            <div class="col-md-2">

                            </div>
                            `
                        });
                    });
                    $("#bodyTransaksiById").html(html)
                    $("#namaPegawai").text(data[0].pegawai.nama)
                    $("#mejaNomor").text(data[0].meja_id)
                }
            });
        }

        function renderTransaksiPegawai() {
            $('#allTransaksi').addClass("transaksi")
            $('#byPegawai').show()
            $('#btnTransaksi').removeClass('btn-info')
            $('#btnTransaksi').addClass('btn-primary')
            $('#btnTransaksi').text('Lihat Seluruh Transaksi')
            $('#btnTransaksi').attr('onclick', 'renderAllTransaksi()')
            $('#filter').addClass('transaksi')
        }
    </script>
@endsection
