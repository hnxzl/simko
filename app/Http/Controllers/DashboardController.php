<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard - Full overview of everything
     */
    public function admin(Request $request)
    {
        $filterMonth = (int) $request->input('chart_month', now()->month);
        $filterYear  = (int) $request->input('chart_year', now()->year);

        $totalUsers     = User::count();
        $totalKaryawan  = User::where('role', 'karyawan')->count();

        $kendaraanTersedia  = Vehicle::where('status', 'available')->count();
        $kendaraanOperasi   = Vehicle::where('status', 'in_use')->count();
        $kendaraanPerbaikan = Vehicle::where('status', 'maintenance')->count();
        $totalKendaraan     = Vehicle::count();

        $totalDrivers   = Driver::count();
        $driversAktif   = Driver::where('status', 'aktif')->count();

        $borrowChart = BorrowRequest::selectRaw('DAY(start_at) AS day, COUNT(*) AS total')
            ->whereMonth('start_at', $filterMonth)
            ->whereYear('start_at', $filterYear)
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->pluck('total', 'day');

        $chartDaily = [];
        for ($i = 1; $i <= 31; $i++) {
            $chartDaily[$i] = $borrowChart[$i] ?? 0;
        }

        $peminjam = BorrowRequest::with(['user', 'vehicle'])
            ->whereIn('status', ['pending_manager', 'pending_hrd', 'approved', 'active'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', [
            'totalUsers'          => $totalUsers,
            'totalKaryawan'       => $totalKaryawan,
            'kendaraanTersedia'   => $kendaraanTersedia,
            'kendaraanOperasi'    => $kendaraanOperasi,
            'kendaraanPerbaikan'  => $kendaraanPerbaikan,
            'totalKendaraan'      => $totalKendaraan,
            'totalDrivers'        => $totalDrivers,
            'driversAktif'        => $driversAktif,
            'chartDaily'          => $chartDaily,
            'filterMonth'         => $filterMonth,
            'filterYear'          => $filterYear,
            'peminjam'            => $peminjam,
        ]);
    }

    /**
     * HRD Dashboard - Vehicle management, driver assignment, inspections
     */
    public function hrd()
    {
        $kendaraanTersedia  = Vehicle::where('status', 'available')->count();
        $kendaraanOperasi   = Vehicle::where('status', 'in_use')->count();
        $kendaraanPerbaikan = Vehicle::where('status', 'maintenance')->count();

        $pendingAssign = BorrowRequest::with(['user', 'vehicle'])
            ->where('status', 'pending_hrd')
            ->orderBy('created_at', 'desc')
            ->get();

        $activeBorrowings = BorrowRequest::with(['user', 'vehicle', 'driver'])
            ->where('status', 'active')
            ->orderBy('start_at', 'desc')
            ->get();

        return view('hrd.dashboard', [
            'kendaraanTersedia'   => $kendaraanTersedia,
            'kendaraanOperasi'    => $kendaraanOperasi,
            'kendaraanPerbaikan'  => $kendaraanPerbaikan,
            'pendingAssign'       => $pendingAssign,
            'activeBorrowings'    => $activeBorrowings,
        ]);
    }

    /**
     * Karyawan Dashboard - Own borrowings
     */
    public function karyawan()
    {
        $user = auth()->user();

        $kendaraanTersedia  = Vehicle::where('status', 'available')->count();

        $myBorrowings = BorrowRequest::with(['vehicle', 'driver'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('karyawan.dashboard', [
            'user'                => $user,
            'kendaraanTersedia'   => $kendaraanTersedia,
            'myBorrowings'        => $myBorrowings,
        ]);
    }

    /**
     * Manager Dashboard - Pending approvals
     */
    public function manager(Request $request)
    {
        $filterMonth = (int) $request->input('chart_month', now()->month);
        $filterYear  = (int) $request->input('chart_year', now()->year);

        $kendaraanTersedia  = Vehicle::where('status', 'available')->count();
        $kendaraanOperasi   = Vehicle::where('status', 'in_use')->count();
        $kendaraanPerbaikan = Vehicle::where('status', 'maintenance')->count();
        $totalKendaraan     = Vehicle::count();

        $borrowChart = BorrowRequest::selectRaw('DAY(start_at) AS day, COUNT(*) AS total')
            ->whereMonth('start_at', $filterMonth)
            ->whereYear('start_at', $filterYear)
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->pluck('total', 'day');

        $chartDaily = [];
        for ($i = 1; $i <= 31; $i++) {
            $chartDaily[$i] = $borrowChart[$i] ?? 0;
        }

        $pendingApprovals = BorrowRequest::with(['user'])
            ->where('status', 'pending_manager')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('manager.dashboard', [
            'kendaraanTersedia'   => $kendaraanTersedia,
            'kendaraanOperasi'    => $kendaraanOperasi,
            'kendaraanPerbaikan'  => $kendaraanPerbaikan,
            'totalKendaraan'      => $totalKendaraan,
            'chartDaily'          => $chartDaily,
            'filterMonth'         => $filterMonth,
            'filterYear'          => $filterYear,
            'pendingApprovals'    => $pendingApprovals,
        ]);
    }

    /**
     * BoD Dashboard - Read-only overview
     */
    public function bod()
    {
        $kendaraanTersedia  = Vehicle::where('status', 'available')->count();
        $kendaraanOperasi   = Vehicle::where('status', 'in_use')->count();
        $kendaraanPerbaikan = Vehicle::where('status', 'maintenance')->count();

        $allBorrowings = BorrowRequest::with(['user', 'vehicle', 'driver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('bod.dashboard', [
            'kendaraanTersedia'   => $kendaraanTersedia,
            'kendaraanOperasi'    => $kendaraanOperasi,
            'kendaraanPerbaikan'  => $kendaraanPerbaikan,
            'allBorrowings'       => $allBorrowings,
        ]);
    }
}