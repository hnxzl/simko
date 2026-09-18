<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::orderBy('nama_driver', 'asc')->paginate(10);

        return view('drivers.index', compact('drivers'));
    }

    public function show($id)
    {
    $driver = Driver::findOrFail($id);
    return view('drivers.show', compact('driver'));
    }
    
    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_driver' => 'required|string|max:100',
            'nik'         => 'nullable|string|max:30',
            'no_hp'       => 'nullable|string|max:20',
            'jenis_sim'   => 'nullable|string|max:10',
            'alamat'      => 'nullable|string',
            'status'      => 'required|in:aktif,bertugas,nonaktif',
        ]);

        Driver::create($request->all());

        return redirect()->route('drivers.index')
            ->with('success', 'Data driver berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $driver = Driver::findOrFail($id);

        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $request->validate([
            'nama_driver' => 'required|string|max:100',
            'nik'         => 'nullable|string|max:30',
            'no_hp'       => 'nullable|string|max:20',
            'jenis_sim'   => 'nullable|string|max:10',
            'alamat'      => 'nullable|string',
            'status'      => 'required|in:aktif,bertugas,nonaktif',
        ]);

        $driver->update($request->all());

        return redirect()->route('drivers.index')
            ->with('success', 'Data driver berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();

        return redirect()->route('drivers.index')
            ->with('success', 'Data driver berhasil dihapus.');
    }
}