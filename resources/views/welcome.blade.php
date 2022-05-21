@if(Auth::user()->role == "kasir")
    @include('kasir.dashboard')
@elseif(Auth::user()->role == "manager")
    @include('manager.dashboard')
@endif
