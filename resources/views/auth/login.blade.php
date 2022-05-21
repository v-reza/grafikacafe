@extends('master.template')
@section('title', 'Grafika Cafe - Login')
@section('content')
<div class="container-fluid mt-5" style="margin:auto; width: 1000px; height:500px">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-md-8 col-lg-7 col-xl-6">
            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                class="img-fluid" alt="Phone image">
        </div>
        <div class="col-md-7 col-lg-7 col-xl-6">
            <div class="text-center">
                <h2>Multi Login</h2>
            </div>
            <form>
                @csrf
                <div class="form-outline mb-4">
                    <input type="text" id="username" class="form-control form-control-lg" placeholder="Username"
                        required />
                </div>
                <div class="form-outline mb-4">
                    <input type="password" id="password" class="form-control form-control-lg" placeholder="Password"
                        required />
                </div>
                <button type="button" id="myBtn" onclick="prosesLogin()" class="btn btn-primary btn-lg btn-block"
                    style="width: 100%">Sign in</button>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    var usernameKey = document.getElementById("username");
    var passwordKey = document.getElementById("password");
    usernameKey.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("myBtn").click();
        }
    });
    passwordKey.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("myBtn").click();
        }
    });

    function prosesLogin() {
        const username = $('#username').val()
        const password = $('#password').val()
        if (username == "" || password == "") {
            swal({
                title: 'Terjadi Kesalahan',
                icon: 'error',
                text: 'Username / Password wajib diisi'
            })
        } else {
            swal({
                title: 'Apakah anda yakin login?',
                icon: 'warning',
            }).then(function(value) {
                if (value) {
                    $.ajax({
                        type: "POST",
                        url: "/login",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "username": username,
                            "password": password
                        },
                        success: function (response) {
                            swal({
                                title: 'Sukses',
                                text: response.message,
                                icon: 'success'
                            }).then(function (ok) {
                                if(ok) {
                                    location.replace('/')
                                }
                            })
                        },
                        error: function (response){
                            const message = response.responseJSON.message
                            swal({
                                title: 'Terjadi Kesalahan',
                                text: message,
                                icon: 'error'
                            })
                        }
                    });
                }
            })
        }
    }
</script>
@endsection
