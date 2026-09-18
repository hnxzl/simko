<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = BorrowRequest::with(['user', 'vehicle', 'driver']);

        if ($user->isKaryawan()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('nama')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->nama . '%');
            });
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('start_at', $request->tanggal);
        }
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereYear('start_at', $request->tahun)->whereMonth('start_at', $request->bulan);
        }
        if ($request->filled('kendaraan')) {
            $query->where('vehicle_id', $request->kendaraan);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        return view('borrowings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_at'            => 'required|date',
            'end_at'              => 'required|date|after_or_equal:start_at',
            'start_time'          => 'nullable|string',
            'end_time'            => 'nullable|string',
            'purpose_text'        => 'required|string',
            'destination_address' => 'required|string',
            'surat_tugas'         => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $user = Auth::user();
        $today = now()->format('Ymd');
        $countToday = BorrowRequest::whereDate('created_at', now())->count() + 1;
        $sequence = str_pad($countToday, 3, '0', STR_PAD_LEFT);
        $kodePinjam = "BR-{$today}-{$sequence}";

        // Handle PDF upload
        $suratTugasPath = null;
        if ($request->hasFile('surat_tugas')) {
            if (!file_exists(public_path('storage'))) {
                Artisan::call('storage:link');
            }
            $suratTugasPath = $request->file('surat_tugas')->store('surat-tugas', 'public');
        }

        $borrow = BorrowRequest::create([
            'kode_pinjam'         => $kodePinjam,
            'user_id'             => $user->id,
            'vehicle_id'          => null,
            'purpose_text'        => $request->purpose_text,
            'destination_address' => $request->destination_address,
            'start_at'            => $request->start_at,
            'end_at'              => $request->end_at,
            'start_time'          => $request->start_time,
            'end_time'            => $request->end_time,
            'surat_tugas_path'    => $suratTugasPath,
            'status'              => 'pending_manager',
        ]);

        $managers = User::whereIn('role', ['manager', 'admin'])->get();
        foreach ($managers as $m) {
            notify($m->id, "Pengajuan Peminjaman Baru", "Peminjaman {$kodePinjam} oleh {$user->name} menunggu persetujuan Anda.", route('borrowings.show', $borrow->id));
        }

        return redirect()->route('borrowings.index')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function show($id)
    {
        $borrow = BorrowRequest::with(['user', 'vehicle', 'driver', 'inspection'])->findOrFail($id);
        return view('borrowings.show', compact('borrow'));
    }

    public function managerApprove(Request $request, $id)
    {
        $borrow = BorrowRequest::findOrFail($id);
        if (!Auth::user()->canApprove()) abort(403, 'Hanya Manager yang boleh menyetujui.');

        $borrow->update([
            'status'           => 'pending_hrd',
            'manager_approval' => true,
            'manager_notes'    => $request->manager_notes ?? null,
            'approved_by'      => Auth::id(),
            'approved_at'      => now(),
        ]);

        $hrdUsers = User::whereIn('role', ['hrd', 'admin'])->get();
        foreach ($hrdUsers as $hrd) {
            notify($hrd->id, "Peminjaman Disetujui Manager", "Peminjaman {$borrow->kode_pinjam} menunggu penugasan kendaraan/supir.", route('borrowings.show', $borrow->id));
        }
        notify($borrow->user_id, "Peminjaman Disetujui Manager", "Peminjaman Anda {$borrow->kode_pinjam} telah disetujui Manager.", route('borrowings.show', $borrow->id));

        return back()->with('success', 'Peminjaman disetujui dan diteruskan ke HRD.');
    }

    public function managerReject(Request $request, $id)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);
        $borrow = BorrowRequest::findOrFail($id);
        if (!Auth::user()->canApprove()) abort(403, 'Hanya Manager yang boleh menolak.');

        $borrow->update([
            'status'           => 'rejected',
            'manager_approval' => false,
            'manager_notes'    => $request->rejection_reason,
            'rejection_reason' => $request->rejection_reason,
            'approved_by'      => Auth::id(),
            'approved_at'      => now(),
        ]);

        notify($borrow->user_id, "Peminjaman Ditolak", "Peminjaman Anda {$borrow->kode_pinjam} ditolak. Alasan: {$request->rejection_reason}", route('borrowings.show', $borrow->id));

        return back()->with('error', 'Peminjaman ditolak.');
    }

    public function hrdAssign(Request $request, $id)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id'  => 'nullable|exists:drivers,id',
        ]);

        $borrow = BorrowRequest::findOrFail($id);
        if (!Auth::user()->canAssign()) abort(403, 'Hanya HRD yang boleh menugaskan kendaraan.');

        // Ensure workflow sequence: manager must approve first
        if ($borrow->status !== 'pending_hrd') {
            return back()->with('error', 'Peminjaman harus disetujui Manager terlebih dahulu sebelum HRD dapat menugaskan kendaraan.');
        }

        $needBod = $request->has('need_bod_approval');
        $newStatus = $needBod ? 'pending_bod' : 'active';
        
        $borrow->update([
            'status' => $newStatus,
            'vehicle_id' => $request->vehicle_id,
            'driver_id' => $request->driver_id,
            'hrd_notes' => $request->hrd_notes ?? null,
            'need_bod_approval' => $needBod,
        ]);

        // If no BoD needed, sync vehicle/driver status immediately
        if (!$needBod) {
            $borrow->syncVehicleStatus();
            $borrow->syncDriverStatus();
        }

        notify($borrow->user_id, "Kendaraan Telah Ditugaskan", "Peminjaman {$borrow->kode_pinjam} telah ditugaskan kendaraan.", route('borrowings.show', $borrow->id));

        return back()->with('success', 'Kendaraan dan supir berhasil ditugaskan.');
    }

    public function bodApprove(Request $request, $id)
    {
        $borrow = BorrowRequest::findOrFail($id);
        if (!Auth::user()->isBoD()) abort(403);

        // Ensure workflow sequence: must be pending_bod status
        if ($borrow->status !== 'pending_bod') {
            return back()->with('error', 'Peminjaman harus dalam status menunggu BoD.');
        }

        $borrow->update([
            'bod_approval' => true,
            'status' => 'active',
            'bod_notes' => $request->bod_notes ?? null
        ]);
        $borrow->syncVehicleStatus();
        $borrow->syncDriverStatus();

        notify($borrow->user_id, "Peminjaman Disetujui BoD", "Peminjaman {$borrow->kode_pinjam} telah disetujui BoD dan dimulai.", route('borrowings.show', $borrow->id));

        return back()->with('success', 'BoD approval granted. Peminjaman dimulai.');
    }

    public function complete($id)
    {
        $borrow = BorrowRequest::findOrFail($id);
        $borrow->update(['status' => 'completed']);
        $borrow->syncVehicleStatus();
        $borrow->syncDriverStatus();

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman selesai.');
    }

    public function cancel($id)
    {
        $borrow = BorrowRequest::findOrFail($id);
        if ($borrow->user_id !== Auth::id()) abort(403, 'Tidak boleh membatalkan peminjaman orang lain.');
        if (!in_array($borrow->status, ['pending_manager', 'pending_hrd', 'pending_bod', 'approved'])) abort(403, 'Peminjaman tidak dapat dibatalkan.');

        $borrow->update(['status' => 'rejected']);
        $borrow->syncVehicleStatus();
        $borrow->syncDriverStatus();

        return back()->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    public function edit($id)
    {
        $borrow = BorrowRequest::findOrFail($id);
        if ($borrow->status !== 'pending_manager' || $borrow->user_id !== Auth::id()) abort(403);
        return view('borrowings.edit', compact('borrow'));
    }

    public function update(Request $request, $id)
    {
        $borrow = BorrowRequest::findOrFail($id);
        if ($borrow->status !== 'pending_manager' || $borrow->user_id !== Auth::id()) abort(403);

        $request->validate([
            'start_at'            => 'required|date',
            'end_at'              => 'required|date|after_or_equal:start_at',
            'start_time'          => 'nullable|string',
            'end_time'            => 'nullable|string',
            'purpose_text'        => 'required|string',
            'destination_address' => 'required|string',
        ]);

        $borrow->update($request->only(['start_at', 'end_at', 'start_time', 'end_time', 'purpose_text', 'destination_address']));

        return redirect()->route('borrowings.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function downloadSurat($id, $type = 'surat_tugas')
    {
        $borrow = BorrowRequest::findOrFail($id);
        $path = $type === 'lampiran' ? $borrow->lampiran_path : $borrow->surat_tugas_path;

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($path);
    }
}
