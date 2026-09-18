<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // For karyawan, only show available vehicles
        if (strtolower(auth()->user()->role) === 'karyawan') {
            $query->where('status', 'available');
        }

        $vehicles = $query->orderBy('name')->paginate(12)->withQueryString();
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_bmn' => 'required',
            'name' => 'required',
            'year' => 'required',
            'merk' => 'required',
            'plat_nomor' => 'required',
            'photo_path' => 'nullable|image|max:2048'
        ]);

        // Pastikan storage link ada
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }

        $data = $request->except('photo_path');

        // Upload foto bila ada
        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('vehicles', 'public');
        }

        Vehicle::create($data);

        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $vehicle = Vehicle::with([
            'oilChanges' => function ($q) {
                $q->latest('date')->limit(1);
            }
        ])->findOrFail($id);

        $lastOilChangeDate = $vehicle->oilChanges->first()?->date;

        return view('vehicles.show', compact(
            'vehicle',
            'lastOilChangeDate'
        ));
    }

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'kode_bmn' => 'required',
            'name' => 'required',
            'year' => 'required',
            'merk' => 'required',
            'plat_nomor' => 'required',
            'photo_path' => 'nullable|image|max:2048'
        ]);

        // Pastikan storage link ada
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }

        $data = $request->except('photo_path');

        // Update foto jika ada file baru
        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('vehicles', 'public');
        }

        $vehicle->update($data);

        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan berhasil diupdate.');
    }

    public function destroy($id)
    {
        Vehicle::findOrFail($id)->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
    public function disable($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update([
            'status' => 'maintenance',
            'notes' => request('notes') ?? $vehicle->notes,
        ]);

        return back()->with('success', 'Kendaraan berhasil dinonaktifkan.');
    }
    public function enable($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update(['status' => 'available']);

        return back()->with('success', 'Kendaraan berhasil diaktifkan kembali.');
    }
}
