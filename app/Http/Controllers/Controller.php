<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\Helper;
use App\Models\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $idPegawai, $namaPegawai;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $authId = Auth::user()->id;
            $this->idPegawai = Helper::getIdPegawai($authId);
            $this->namaPegawai = Helper::getNamaPegawai($authId);
            return $next($request);
        });
    }

    /**
     * Response API
     *
     * @param bool $error jika tidak error response wajib object, contoh response ["error" => false, "data" => {buat object response disini}]
     * @param object $response gunakan tipe object ketika error false
     * @param string $response gunakan tipe string ketika error true untuk message
      * @param int $status http status code response
     * @return mixed JSON array
     */
    public function resJson($error = false, $response, $status)
    {
        if ($error) {
            $objError = [
                "error" => true,
                "message" => $response
            ];

            return response($objError, $status);
        }

        $objSuccess = [
            "error" => false,
            "data" => $response
        ];

        return response($response, $status);
    }

    public function generateId()
    {
        return Str::random(16);
    }

    public function authUser()
    {
        return Auth::user();
    }

    public function createLog($act)
    {
        return Log::create([
            'pegawai_id' => $this->idPegawai,
            'aktivitas' => $act
        ]);
    }
}
