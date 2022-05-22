<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Auth\Controller;
use App\Models\Manager;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function render()
    {
        $data = User::with('Pegawai')->where('role', 'kasir')->get();
        $manager = User::with('Manager')->where('role', 'manager')->get();

        return view('admin.user.index', compact('data', 'manager'));
    }

    public function hapusKasir($id)
    {
        $user = User::find($id);
        $pegawai = Pegawai::where('user_id', $user->id);

        $user->delete();
        $pegawai->delete();

        return $this->resJson(false, ['message' => 'Berhasil hapus data'], 200);
    }

    public function hapusManager($id)
    {
        $user = User::find($id);
        $manager = Manager::where('user_id', $user->id);

        $user->delete();
        $manager->delete();
        return $this->resJson(false, ['message' => 'Berhasil hapus data'], 200);
    }

    public function getById($id)
    {
        $user = User::find($id);
        if ($user->role == "kasir") {
            $data = Pegawai::where('user_id', $user->id)->first();
        }

        if ($user->role == "manager") {
            $data = Manager::where('user_id', $user->id)->first();
        }
        return $this->resJson(false, ['data' => $data, 'role' => $user->role], 200);
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);

        if ($user->role == "kasir") {
            $user->update([
                'role' => $request->role
            ]);

            Pegawai::where('user_id', $user->id)->update([
                'nama' => $request->nama
            ]);
        } else if ($user->role == "manager") {
            $user->update([
                'role' => $request->role
            ]);

            Manager::where('user_id', $user->id)->update([
                'nama' => $request->nama
            ]);
        }

        return $this->resJson(false, ['message' => 'Berhasil update data'], 200);
    }

    public function create(Request $request, $key)
    {
        if ($key == "kasir") {
            $user = User::create([
                'username' => $request->username,
                'password'=> bcrypt($request->password),
                'role' => $request->role
            ]);
            Pegawai::create([
                'user_id' => $user->id,
                'nama' => $request->nama
            ]);
        } else if ($key == "manager") {
            $user = User::create([
                'username' => $request->username,
                'password'=> bcrypt($request->password),
                'role' => $request->role
            ]);

            Manager::create([
                'user_id' => $user->id,
                'nama' => $request->nama
            ]);
        }

        return back();
    }
}
