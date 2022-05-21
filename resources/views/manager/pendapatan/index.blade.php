@extends('manager.master.template')
@section('title', 'Manager Pendapatan')
@section('customcss')
    <style>
        .hidden {
            display: none;
        }

    </style>
@endsection
@section('content')
    <h2>Laporan Pendapatan</h2>
    <div class="row">
        <div class="col-sm-4">
            <button class="btn btn-outline-primary" onclick="filterSemua()" id="semua">Semua</button>
            <button class="btn btn-outline-success" id="harian" onclick="filterHarian()">Harian</button>
            <button class="btn btn-outline-warning" id="bulanan" onclick="filterBulanan()">Bulanan</button>
        </div>
    </div>
    <br>
    <div class="row hidden" id="harianInput">
        <div class="col-md-4">
            <label for="tanggalFilter" id="labelHarian">Periode Tanggal</label>
            <input type="date" id="tanggalFilter" class="form-control">
        </div>
        <div class="col-md-4">
            <br>
            <button class="btn btn-dark" id="cariHarian">Cari</button>
        </div>
    </div>
    <div class="row hidden" id="bulananInput">
        <h6>Periode</h6>
        <div class="col-md-4">
            <label for="tanggalFilterDari">Dari Tanggal</label>
            <input type="date" id="tanggalFilterDari" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="tanggalFilterSampai">Sampai Tanggal</label>
            <input type="date" id="tanggalFilterSampai" class="form-control">
        </div>
        <div class="col-md-4">
            <br>
            <button class="btn btn-dark" id="cariBulanan">Cari</button>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Laporan Pendapatan</h5>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="card">
                        <div class="card-body">
                            Total Pendapatan : <span id="totalPendapatan"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function filterSemua() {
            $("#harian").removeClass("btn-success")
            $("#harian").addClass("btn-outline-success")
            $("#bulanan").removeClass("btn-warning")
            $("#bulanan").addClass("btn-outline-warning")
            $("#semua").removeClass("btn-outline-primary")
            $("#semua").addClass("btn-primary")

            /* Input */
            $("#harianInput").addClass("hidden")
            $("#bulananInput").addClass("hidden")

            /* Modal */

            $("#modalCenter").modal('toggle')

            /* Code Ajax */
            $.ajax({
                type: "GET",
                url: "/manager/renderPendapatan",
                success: function(response) {
                    var data = response.data
                    $("#totalPendapatan").text('Rp. ' + formatToNumber(data))
                }
            });
        }

        function filterHarian() {
            $("#harian").removeClass("btn-outline-success")
            $("#harian").addClass("btn-success")
            $("#bulanan").removeClass("btn-warning")
            $("#bulanan").addClass("btn-outline-warning")
            $("#semua").removeClass("btn-primary")
            $("#semua").addClass("btn-outline-primary")

            /* Input */
            $("#harianInput").removeClass("hidden")
            $("#bulananInput").addClass("hidden")

            /* Modal */

            $("#cariHarian").on("click", function() {
                const dateVal = $("#tanggalFilter").val()
                if (dateVal == "") {
                    swal({
                        title: "Terjadi Kesalahan",
                        text: "Periode Tanggal harus diisi!",
                        icon: "error"
                    })
                } else {
                    $("#modalCenter").modal('toggle')
                    $.ajax({
                        type: "POST",
                        url: "/manager/filterHarian",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "date": dateVal
                        },
                        success: function(response) {
                            var data = response.data
                            $("#totalPendapatan").text('Rp. ' + formatToNumber(data))
                        }
                    });
                }
            })
        }

        function filterBulanan() {
            $("#harian").removeClass("btn-success")
            $("#harian").addClass("btn-outline-success")
            $("#bulanan").removeClass("btn-outline-warning")
            $("#bulanan").addClass("btn-warning")
            $("#semua").removeClass("btn-primary")
            $("#semua").addClass("btn-outline-primary")

            /* Input */
            $("#harianInput").addClass("hidden")
            $("#bulananInput").removeClass("hidden")

            $("#cariBulanan").on("click", function () {
                const tglDari = $("#tanggalFilterDari").val()
                const tglSampai = $("#tanggalFilterSampai").val()

                if (tglDari == "" || tglSampai == "") {
                    swal({
                        title: "Terjadi Kesalahan",
                        text: "Periode Tanggal harus diisi!",
                        icon: "error"
                    })
                } else {
                    $("#modalCenter").modal('toggle')
                    $.ajax({
                        type: "POST",
                        url: "/manager/filterBulanan",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "tglDari": tglDari,
                            "tglSampai": tglSampai
                        },
                        success: function (response) {
                            var data = response.data
                            $("#totalPendapatan").text('Rp. ' + formatToNumber(data))
                        }
                    });
                }
            })
        }
    </script>
@endsection
