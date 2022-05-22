@extends('admin.master.template')
@section('title', 'Dashboard Admin - User')
@section('customcss')
    <style>
        .hidden {
            display: none
        }

    </style>
@endsection
@section('content')
    <h4>List User</h4>
    <div class="row">
        <div class="col-sm-4">
            <button class="btn btn-outline-primary" onclick="getKasir()" id="btnKasir">Kasir</button>
            <button class="btn btn-outline-success" id="btnManager" onclick="getManager()">Manager</button>
        </div>
    </div>
    {{-- Kasir --}}
    @include('admin.user.kasir.index')
    {{-- Manager --}}
    @include('admin.user.manager.index')

    <!-- Modal -->
    <div class="modal fade" id="modalCenter" role="dialog" aria-labelledby="modalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit</h5>
                </div>
                <div class="modal-body" id="modalBody">
                    <form id="update">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nama</label>
                            <input type="hidden" id="idHidden" name="id">
                            <input type="text" class="form-control" id="nama" name="nama" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="kasir">Kasir</option>
                                <option value="manager">Manager</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="createModal" role="dialog" aria-labelledby="createModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Buat Data</h5>
                </div>
                <div class="modal-body" id="modalBody">
                    <form action="/admin/createUser/kasir" id="create" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Username</label>
                            <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Password</label>
                            <input type="password" class="form-control" id="password" name="password" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nama</label>
                            <input type="hidden" id="idHidden" name="id">
                            <input type="text" class="form-control" id="nama" name="nama" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="kasir">Kasir</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="createModalManager" role="dialog" aria-labelledby="createModalManagerTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Buat Data</h5>
                </div>
                <div class="modal-body" id="modalBody">
                    <form action="/admin/createUser/manager" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Username</label>
                            <input type="text" class="form-control" name="username" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Password</label>
                            <input type="password" class="form-control" name="password" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nama</label>
                            <input type="text" class="form-control" name="nama" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Role</label>
                            <select class="form-control" name="role">
                                <option value="manager">Manager</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function getKasir() {
            $("#kasir").removeClass("hidden")
            $("#manager").addClass("hidden")
            $("#btnKasir").removeClass("btn-outline-primary")
            $("#btnKasir").addClass("btn-primary")
            $("#btnManager").addClass("btn-outline-success")
            $("#btnManager").removeClass("btn-success")
        }

        function getManager() {
            $("#kasir").addClass("hidden")
            $("#manager").removeClass("hidden")
            $("#btnKasir").addClass("btn-outline-primary")
            $("#btnKasir").removeClass("btn-primary")
            $("#btnManager").removeClass("btn-outline-success")
            $("#btnManager").addClass("btn-success")
        }

        function hapusKasir(kasir) {
            const dataId = $(kasir).data("id")
            swal({
                title: "Apakah anda yakin?",
                icon: "warning",
                buttons: ["Tidak", "Ya"]
            }).then(function(ok) {
                if (ok) {
                    $.ajax({
                        type: "GET",
                        url: "/admin/hapusKasir/" + dataId,
                        success: function(response) {
                            var data = response.data
                            location.replace('/admin/user')
                        }
                    });
                }
            })
        }

        function hapusManager(manager) {
            const dataId = $(manager).data("id")
            swal({
                title: "Apakah anda yakin?",
                icon: "warning",
                buttons: ["Tidak", "Ya"]
            }).then(function(ok) {
                if (ok) {
                    $.ajax({
                        type: "GET",
                        url: "/admin/hapusManager/" + dataId,
                        success: function(response) {
                            var data = response.data
                            location.replace('/admin/user')
                        }
                    });
                }
            })
        }

        function edit(val) {
            const dataId = $(val).data("id")
            $.ajax({
                type: "GET",
                url: "/admin/getById/" + dataId,
                success: function (response) {
                    $("#modalCenter").modal('toggle')
                    var data = response.data
                    if (response.role == "kasir") {
                        $('form #nama').attr('value', data.nama)
                        $('form select[name=role] option[value=kasir]').attr('selected', 'selected')
                    } else if (response.role == "manager") {
                        $('form #nama').attr('value', data.nama)
                        $('form select[name=role] option[value=manager]').attr('selected', 'selected')
                    }
                    $('#update').submit(function (event) {
                        event.preventDefault();
                        const nama = $('#nama').val()
                        const role = $('select[name=role] option').filter(':selected').val()
                        swal({
                            title: "Yakin ingin update?",
                            icon: "warning",
                            buttons: ["Tidak", "Ya"]
                        }).then(function (up) {
                            if (up) {
                                $.ajax({
                                    type: "POST",
                                    url: "/admin/updateUser",
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        "id": dataId,
                                        "nama": nama,
                                        "role": role
                                    },
                                    success: function (response) {
                                        location.replace('/admin/user')
                                    }
                                });
                            }
                        })
                    });
                }
            });
        }

        function create(key) {
            if (key == "kasir") {
                $("#createModal").modal('toggle')
            } else if (key == "manager") {
                $("#createModalManager").modal('toggle')
            }
        }
    </script>
@endsection
