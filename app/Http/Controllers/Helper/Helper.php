<?php

namespace App\Http\Controllers\Helper;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Helper
{
    public static function getIdPegawai($id)
    {
        $authUser = User::find($id);
        $authPegawai = Pegawai::where('user_id', $authUser->id)->first();
        return $authPegawai->id;
    }

    public static function getNamaPegawai($id)
    {
        $authUser = User::find($id);
        $authPegawai = Pegawai::where('user_id', $authUser->id)->first();
        return $authPegawai->nama;
    }
}
