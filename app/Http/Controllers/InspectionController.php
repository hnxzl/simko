<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\Auth;

class InspectionController extends Controller
{
    /**
     * Form inspeksi kendaraan saat kembali (HRD)
     */
    public function create($borrow_id)
    {
        $borrow = BorrowRequest::with(['user', 'vehicle', 'driver'])->findOrFail($borrow_id);

        if (!Auth::user()->canAssign()) {
            abort(403, 'Hanya HRD yang boleh melakukan inspeksi.');
        }

        return view('inspections.create', compact('borrow'));
    }

    /**
     * Simpan hasil inspeksi
     * Jika is_damaged = true, ubah status kendaraan menjadi 'maintenance'
     */
    public function store(Request $request, $borrow_id)
    {
        $request->validate([
            'fuel_level'              => 'nullable|integer|min:0|max:100',
            'last_km'                 => 'nullable|integer|min:0',
            'physical_condition_notes' => 'nullable|string',
            'is_damaged'              => 'required|boolean',
            'damage_photos'           => 'nullable|image|max:2048',
        ]);

        $borrow = BorrowRequest::findOrFail($borrow_id);

        if (!Auth::user()->canAssign()) {
            abort(403);
        }

        $photoPath = null;
        if ($request->hasFile('damage_photos')) {
            $photoPath = $request->file('damage_photos')->store('damage_photos', 'public');
        }

        $inspection = Inspection::create([
            'borrow_request_id'        => $borrow_id,
            'fuel_level'               => $request->fuel_level,
            'last_km'                  => $request->last_km,
            'physical_condition_notes' => $request->physical_condition_notes,
            'is_damaged'               => $request->is_damaged,
            'damage_photos'            => $photoPath,
            'inspected_by'             => Auth::id(),
        ]);

        // Jika rusak, ubah status kendaraan menjadi maintenance
        if ($request->is_damaged && $borrow->vehicle) {
            $borrow->vehicle->update(['status' => 'maintenance']);
        }

        // Update status peminjaman menjadi completed
        $borrow->update(['status' => 'completed']);
        $borrow->syncVehicleStatus();
        $borrow->syncDriverStatus();

        // Notify karyawan
        notify(
            $borrow->user_id,
            "Inspeksi Selesai",
            "Peminjaman {$borrow->kode_pinjam} telah selesai diinspeksi oleh HRD.",
            route('borrowings.show', $borrow->id)
        );

        return redirect()->route('borrowings.show', $borrow_id)->with('success', 'Inspeksi berhasil disimpan.');
    }

    /**
     * Detail inspeksi
     */
    public function show($id)
    {
        $inspection = Inspection::with(['borrowRequest.user', 'borrowRequest.vehicle', 'inspector'])->findOrFail($id);
        return view('inspections.show', compact('inspection'));
    }
}
