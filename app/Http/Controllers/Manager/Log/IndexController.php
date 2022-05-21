<?php

namespace App\Http\Controllers\Manager\Log;

use App\Http\Controllers\Auth\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function render()
    {
        $data = Log::paginate(10);
        return view('manager.log.index', compact('data'));
    }
}
