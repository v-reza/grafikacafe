<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class IndexController extends Controller
{
    public function render()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $cekUser = User::where('username', $request->username)->first();

        if (!$cekUser || !Hash::check($request->password, $cekUser->password)) {
            return $this->resJson(true, 'Error Kredential', 404);
        }

        Auth::login($cekUser);
        return $this->resJson(false, [
            'message' => 'Berhasil Login sebagai ' . $cekUser->role,
            'role' => $cekUser->role
        ], 200);
    }

    public function destroySession()
    {
        Auth::logout();
        return $this->resJson(false, ['message' => 'Berhasil Logout'], 200);
    }
}
