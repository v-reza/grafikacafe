@if(Auth::user()->role == "kasir")
    @include('kasir.dashboard')
@elseif(Auth::user()->role == "manager")
    @include('manager.dashboard')
@elseif(Auth::user()->role == "admin")
    @include('admin.dashboard')
@endif
