function logout()
{
    swal({
        title: 'Yakin ingin keluar?',
        icon: 'warning',
        buttons: ["Batal", "Ya!"],
    }).then(function (logout) {
        if (logout) {
            $.ajax({
                type: "POST",
                url: "/logout",
                data: {
                    "_token": $('meta[name="_token"]').attr('content')
                },
                success: function (response) {
                    location.replace('/login')
                }
            });
        }
    })
}

const prefixManager = '/manager'

function formatToNumber(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
