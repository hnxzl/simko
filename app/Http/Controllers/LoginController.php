<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function storelogin(Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute belum terisi.',
            'email' => 'Kolom :attribute harus berformat email yang valid.',
            'password.max' => 'Kolom password maksimal berisi 50 karakter.',
        ];

        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|max:50',
        ], $messages);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->role) {
                Auth::logout();
                flash()->addError('<b>Error!</b><br>Akun tidak memiliki role.');
                return redirect()->route('login');
            }

            $role = strtolower($user->role);

            switch ($role) {
                case 'admin':
                    flash()->addSuccess('<b>Berhasil!</b><br>Selamat datang, Administrator.');
                    return redirect()->route('admin.dashboard');

                case 'hrd':
                    flash()->addSuccess('<b>Berhasil!</b><br>Selamat datang, HRD/GA.');
                    return redirect()->route('hrd.dashboard');

                case 'karyawan':
                    flash()->addSuccess('<b>Berhasil!</b><br>Selamat datang, Karyawan.');
                    return redirect()->route('karyawan.dashboard');

                case 'manager':
                    flash()->addSuccess('<b>Berhasil!</b><br>Selamat datang, Manager.');
                    return redirect()->route('manager.dashboard');

                case 'bod':
                    flash()->addSuccess('<b>Berhasil!</b><br>Selamat datang, Board of Directors.');
                    return redirect()->route('bod.dashboard');

                default:
                    Auth::logout();
                    flash()->addError('<b>Error!</b><br>Role tidak dikenali.');
                    return redirect()->route('login');
            }
        }

        flash()->addError('<b>Error!</b><br>Email atau sandi tidak sesuai.');
        return back()->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        flash()->addInfo('<b>Info</b><br>Anda telah logout.', ['layout' => 'topCenter']);
        return redirect()->route('login');
    }
}
