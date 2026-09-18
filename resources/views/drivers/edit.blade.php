@extends('layouts.app')

@section('title', 'Ubah Driver')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Ubah Driver</h1>
        <p class="page-subtitle">Perbarui informasi driver {{ $driver->nama_driver }}</p>
    </div>
    <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('drivers.update', $driver->id) }}" method="POST" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-md-6">
                <label class="form-label">Nama Driver <span class="text-danger">*</span></label>
                <input type="text"
                       name="nama_driver"
                       value="{{ old('nama_driver', $driver->nama_driver) }}"
                       class="form-control @error('nama_driver') is-invalid @enderror"
                       required>
                @error('nama_driver')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">NIK</label>
                <input type="text"
                       name="nik"
                       value="{{ old('nik', $driver->nik) }}"
                       class="form-control @error('nik') is-invalid @enderror">
                @error('nik')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">No HP</label>
                <input type="text"
                       name="no_hp"
                       value="{{ old('no_hp', $driver->no_hp) }}"
                       class="form-control @error('no_hp') is-invalid @enderror">
                @error('no_hp')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Jenis SIM</label>
                <select name="jenis_sim" class="form-select @error('jenis_sim') is-invalid @enderror">
                    <option value="">Pilih Jenis SIM</option>
                    <option value="A" {{ old('jenis_sim', $driver->jenis_sim) == 'A' ? 'selected' : '' }}>SIM A</option>
                    <option value="B1" {{ old('jenis_sim', $driver->jenis_sim) == 'B1' ? 'selected' : '' }}>SIM B1</option>
                    <option value="B2" {{ old('jenis_sim', $driver->jenis_sim) == 'B2' ? 'selected' : '' }}>SIM B2</option>
                </select>
                @error('jenis_sim')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="aktif" {{ old('status', $driver->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="bertugas" {{ old('status', $driver->status) == 'bertugas' ? 'selected' : '' }}>Bertugas</option>
                    <option value="nonaktif" {{ old('status', $driver->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-12">
                <label class="form-label">Alamat</label>
                <textarea name="alamat"
                          class="form-control @error('alamat') is-invalid @enderror"
                          rows="3">{{ old('alamat', $driver->alamat) }}</textarea>
                @error('alamat')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-12 d-flex gap-2 pt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save me-2"></i>Update
                </button>
                <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
