@extends('master.template')
@section('title', 'Dashboard Kasir')
@section('stylecss')
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.0.0/mdb.min.css" rel="stylesheet" />
@endsection
@section('content')
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><i class="fas fa-coffee"></i>&nbsp;Grafika Cafe</a>
            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Navbar brand -->

                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <span class="nav-link" id="makanan" style="cursor: pointer"
                            onclick="filterCategory('makanan')"><i class="fas fa-hotdog"></i>&nbsp;Makanan</span>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link" id="minuman" style="cursor: pointer"
                            onclick="filterCategory('minuman')"><i class="fas fa-cocktail"></i>&nbsp;Minuman</span>
                    </li>
                    @include('kasir.riwayat.index')
                </ul>
                <!-- Left links -->
            </div>
            <!-- Collapsible wrapper -->

            <!-- Right elements -->
            <div class="d-flex align-items-center">
                @include('kasir.keranjang.index')
                <!-- Avatar -->
                <button class="btn btn-warning" type="button" onclick="logout()"><i
                        class="fas fa-sign-out-alt"></i></button>&nbsp;
            </div>
            <!-- Right elements -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->

    {{-- Modal Pembayaran --}}
    @include('kasir.checkout.index')
    {{-- Produk --}}
    @include('kasir.produk.index')
@endsection
@section('script')
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.0.0/mdb.min.js"></script>
    <script>
        function loadAll() {
            loadProduk()
            loadKeranjang()
            loadTotalPembayaran()
            loadRiwayatTransaksi()
        }
        $(document).ready(function() {
            loadAll()
        })

        function loadProduk() {
            var produk = ''
            $.ajax({
                type: "GET",
                url: "/renderProduk",
                success: function(response) {
                    var data = response.data
                    $.each(data, function(i) {
                        produk += `
                    <div class="col-md-3 mb-2 mb-2">
                        <div class="card" style="width: 18rem;">
                        <img src="${data[i].gambar}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">${data[i].nama_produk}</h5>
                            <p class="card-text">Harga : Rp. ${formatToNumber(data[i].harga)}</p>
                            <p class="card-text">Kategori : ${data[i].kategori}</p>
                            <span class="btn btn-primary" onclick="tambahPesanan(this)"
                                data-id="${data[i].id}"
                                data-nama="${data[i].nama_produk}">Tambah Pesanan</span>
                        </div>
                        </div>
                    </div>
                    `
                        $("#produk").html(produk)
                    })
                }
            });
        }

        function loadKeranjang() {
            let isiKeranjang = ''
            let isiPembayaran = ''
            $.ajax({
                type: "GET",
                url: "/renderKeranjang",
                success: function(response) {
                    $('#countKeranjang').text(response.total)
                    var data = response.keranjang
                    if (data.length == 0) {
                        isiKeranjang += `
                        <div><span class="text-left">Kosong</span></div>
                        `
                        isiPembayaran += `
                            <div></div>
                        `
                        $("#cart").html(isiKeranjang)
                        $("#pesanan").html(isiPembayaran)
                        $("#checkout").hide()
                        $("#bayarKeranjang").hide()
                    } else {
                        $.each(data, function(i) {
                            const totHarga = data[i].produk.harga * data[i].jumlah
                            isiKeranjang += `
                            <div class="row">
                                <div class="col-md-6">
                                    <img class="img-thumbnail" style="width: 10%px; height: 150px" src="${data[i].produk.gambar}"/>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-left">Nama: ${data[i].produk.nama_produk}</span><br>
                                    <span class="text-left">Harga: ${data[i].produk.harga}</span><br>
                                    <span class="text-left">Jumlah: ${data[i].jumlah}</span><br>
                                    <span class="text-left">Total Harga: Rp. ${formatToNumber(totHarga)}</span><br>
                                    <button type="button" class="btn btn-danger" data-id="${data[i].id}" data-nama="${data[i].produk.nama_produk}" onclick="hapusPesanan(this)">Hapus Pesanan</button>
                                </div>
                            </div>
                            `
                            isiPembayaran += `
                            <div class="col-md-2">
                                <img
                                class="img-thumbnail"
                                src="${data[i].produk.gambar}" alt="">
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <span class="text-left" id="nama">Nama : ${data[i].produk.nama_produk}</span>
                                    <span class="text-left" id="jumlah">Jumlah: ${data[i].jumlah}</span>
                                    <span class="text-left" id="hargaSatuan">Harga / Satuan: Rp. ${formatToNumber(data[i].produk.harga)}</span>
                                    <span class="text-left" class="totalHarga" id="totalHarga">Total Harga: Rp. ${formatToNumber(totHarga)}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h4>Tambah Pesanan</h4>
                                <div class="row d-flex justify-content-start">
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-dark" data-id="${data[i].id}" onclick="tambahPesanModal(this)">+</button>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-dark" data-id="${data[i].id}" onclick="kurangPesanModal(this)">-</button>
                                    </div>
                                </div>
                            </div>
                            `
                        })
                        $("#cart").html(isiKeranjang)
                        $("#pesanan").html(isiPembayaran)
                        $("#checkout").show()
                        $("#bayarKeranjang").show()
                    }
                }
            });
        }

        function tambahPesanan(produk) {
            const title = $(produk).data('nama')
            const produkId = $(produk).data('id')
            swal({
                title: 'Tambah Pesanan?',
                text: `Menu ${title}`,
                icon: 'warning',
                buttons: ["Batal", "Ya!"],
            }).then(function(tambah) {
                if (tambah) {
                    $.ajax({
                        type: "POST",
                        url: "/addToCart",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "produk_id": produkId,
                        },
                        success: function(response) {
                            swal({
                                title: 'Sukses',
                                text: 'Berhasil menambahkan menu ke keranjang',
                                icon: 'success'
                            })
                            loadAll()
                        },
                        error: function(response) {
                            console.log("error: " + response)
                        }
                    });
                }
            })
        }

        function hapusPesanan(produk) {
            const keranjangId = $(produk).data('id')
            console.log(keranjangId)
            const title = $(produk).data('nama')
            swal({
                title: 'Hapus Pesanan?',
                text: `Menu ${title}`,
                icon: 'warning',
                buttons: ["Batal", "Ya!"],
            }).then(function(hapus) {
                if (hapus) {
                    $.ajax({
                        type: "POST",
                        url: "/deleteFromCart",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "keranjang_id": keranjangId,
                        },
                        success: function(response) {
                            swal({
                                title: 'Sukses',
                                text: response.message,
                                icon: 'success'
                            })
                            loadAll()
                        },
                        error: function(response) {
                            console.log("error: " + response)
                        }
                    });
                }
            })
        }

        function loadTotalPembayaran() {
            $.ajax({
                type: "GET",
                url: "/renderTotalPembayaran",
                success: function(response) {
                    var data = response.data
                    $("#totalHarusDibayar").text("Rp. " + formatToNumber(data.total_pembayaran))
                }
            });
        }

        function filterCategory(key) {
            var produk = ''
            $.ajax({
                type: "GET",
                url: "/filterKategori/" + key,
                success: function(response) {
                    var data = response.data
                    $.each(data, function(i) {
                        produk += `
                        <div class="col-md-3 mb-2 mb-2">
                        <div class="card" style="width: 18rem;">
                        <img src="${data[i].gambar}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">${data[i].nama_produk}</h5>
                            <p class="card-text">Harga : Rp. ${formatToNumber(data[i].harga)}</p>
                            <p class="card-text">Kategori : ${data[i].kategori}</p>
                            <span class="btn btn-primary" onclick="tambahPesanan(this)"
                                data-id="${data[i].id}"
                                data-nama="${data[i].nama_produk}">Tambah Pesanan</span>
                        </div>
                        </div>
                    </div>
                `
                    })
                    $("#produk").html(produk)
                    if (key == 'makanan') {
                        $("#minuman").removeClass('active')
                        $("#makanan").addClass('active')
                    } else if (key == 'minuman') {
                        $("#makanan").removeClass('active')
                        $("#minuman").addClass('active')
                    }
                }
            });
        }

        function kurangPesanModal(modal) {
            const keranjangId = $(modal).data('id')
            swal({
                title: "Yakin ingin mengurangi pesan?",
                icon: 'warning',
                buttons: ["Batal", "Ya!"],
            }).then(function(tambah) {
                if (tambah) {
                    $.ajax({
                        type: "POST",
                        url: "/kurangPesananModal",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "keranjangId": keranjangId
                        },
                        success: function(response) {
                            swal({
                                title: 'Sukses',
                                text: response.message,
                                icon: 'success'
                            })
                            loadAll()
                        }
                    });
                }
            })
        }

        function tambahPesanModal(modal) {
            const keranjangId = $(modal).data('id')
            swal({
                title: "Yakin ingin menambah pesan?",
                icon: 'warning',
                buttons: ["Batal", "Ya!"],
            }).then(function(tambah) {
                if (tambah) {
                    $.ajax({
                        type: "POST",
                        url: "/addPesananModal",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "keranjangId": keranjangId
                        },
                        success: function(response) {
                            swal({
                                title: 'Sukses',
                                text: response.message,
                                icon: 'success'
                            })
                            loadAll()
                        }
                    });
                }
            })
        }

        function bayar() {
            let meja = ''
            var htmlContent = document.createElement("div");
            htmlContent.innerHTML =
                "<input type=\"text\" id=\"tunai\" class=\"form-control\" placeholder=\"Masukkan Tunai : Contoh 100000\" autocomplete=\"off\"><br><br><select class=\"form-select\" name=\"meja\" id=\"meja\" aria-label=\".form-select-sm example\"> <option selected>Pilih Meja</option></select>";
            $.ajax({
                type: "GET",
                url: "/renderMeja",
                success: function(response) {
                    var data = response.data
                    $.each(data, function(i) {
                        meja += `
                            <option value="${data[i].meja_no}">Meja ${data[i].meja_no}</option>
                        `
                    })
                    $("#meja").append(meja)
                }
            });

            swal({
                title: 'Bayar pesananmu sekarang!',
                text: 'Bayar pesananmu menggunakan tunai',
                icon: 'warning',
                content: htmlContent,
                buttons: ["Batal", "Bayar"],
            }).then(function(bayar) {
                const tunaiValue = $("#tunai").val()
                const pilihMeja = $('select[name=meja] option').filter(':selected').val()
                if (bayar) {
                    if (pilihMeja == "Pilih Meja") {
                        swal({
                            title: 'Terjadi Kesalahan',
                            text: 'Meja harus dipilih',
                            icon: 'error'
                        })
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/addTransaksi",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "tunai": tunaiValue,
                                "meja": pilihMeja
                            },
                            success: function(response) {
                                swal({
                                    title: 'Sukses',
                                    text: response.message,
                                    icon: 'success'
                                })
                                loadAll()
                            },
                            error: function(response) {
                                const msg = response.responseJSON.message
                                swal({
                                    title: 'Terjadi Kesalahan',
                                    text: msg,
                                    icon: 'error'
                                })
                            }
                        });

                    }
                }
            })
        }

        function loadRiwayatTransaksi() {
            let isiRiwayat = ''
            $.ajax({
                type: "GET",
                url: "/renderRiwayatTransaksi",
                success: function(response) {
                    var data = response.data
                    if (data.length == 0) {
                        isiRiwayat += `
                            <div></div>
                        `
                        $("#riwayatTransaksi").html(isiRiwayat)
                    } else {
                        $.each(data, function(i, transaksi) {
                            $.each(transaksi.keranjang, function(k, keranjang) {
                                var totHarga = data[i].keranjang[k].produk.harga * data[i].keranjang[k].jumlah

                                isiRiwayat += `
                                <div class="col-md-2">
                                    <img
                                    class="img-thumbnail"
                                    src="${data[i].keranjang[k].produk.gambar}" alt="">
                                </div>
                                <div class="col-md-6">
                                    <h5>Detail Pesanan</h5>
                                    <div class="row">
                                        <span class="text-left" id="nama">Nama : ${data[i].keranjang[k].produk.nama_produk}</span>
                                        <span class="text-left" id="nama">Jumlah : ${data[i].keranjang[k].jumlah}</span>
                                        <span class="text-left" id="nama">Harga / Satuan : Rp. ${formatToNumber(data[i].keranjang[k].produk.harga)}</span>
                                        <span class="text-left" id="nama">Total Harga : ${formatToNumber(totHarga)}</span>
                                    </div>
                                    </div>
                                <div class="col-md-4">
                                    <h5>Detail Transaksi</h5>
                                    <div class="row">
                                        <span class="text-left" id="nama">Transaksi ID : ${data[i].id}</span>
                                        <span class="text-left" id="nama">Meja No : ${data[i].meja_id}</span>
                                    </div>
                                </div>
                                `
                            })
                        })
                        $("#riwayatTransaksi").html(isiRiwayat)
                    }
                }
            });
        }
    </script>
@endsection
