<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Role user
        $userRole = strtolower(Auth::user()->role);

        // Normalisasi role di parameter middleware
        $allowed = array_map('strtolower', $roles);

        // dd([
        //     'userRole' => $userRole,
        //     'allowed' => $allowed,
        // ]);

        // Jika user tidak punya salah satu role tersebut
        if (!in_array($userRole, $allowed)) {
            flash()->addError('<b>Akses Ditolak!</b><br>Anda tidak memiliki izin untuk mengakses halaman ini.');
            
            // Redirect ke dashboard sesuai role user
            $dashboardRoute = match($userRole) {
                'admin' => 'admin.dashboard',
                'hrd' => 'hrd.dashboard',
                'karyawan' => 'karyawan.dashboard',
                'manager' => 'manager.dashboard',
                'bod' => 'bod.dashboard',
                default => 'login',
            };
            
            return redirect()->route($dashboardRoute);
        }

        return $next($request);
    }
}
