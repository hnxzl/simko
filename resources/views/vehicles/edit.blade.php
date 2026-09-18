@extends('layouts.app')

@section('title', 'Edit Kendaraan')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Kendaraan</h1>
        <p class="page-subtitle">{{ $vehicle->name }}</p>
    </div>
    <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
                <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    @method('PUT')

                    {{-- Kode Kendaraan --}}
                    <div class="col-md-6">
                        <label class="form-label">Kode Kendaraan <span class="text-danger">*</span></label>
                        <input type="text" name="kode_bmn" class="form-control"
                               value="{{ $vehicle->kode_bmn }}" required>
                    </div>

                    {{-- Nama --}}
                    <div class="col-md-6">
                        <label class="form-label">Nama Kendaraan <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ $vehicle->name }}" required>
                    </div>

                    {{-- Merk --}}
                    <div class="col-md-6">
                        <label class="form-label">Merk <span class="text-danger">*</span></label>
                        <input type="text" name="merk" class="form-control"
                               value="{{ $vehicle->merk }}" required>
                    </div>

                    {{-- Plat Nomor --}}
                    <div class="col-md-6">
                        <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                        <input type="text" name="plat_nomor" class="form-control"
                               value="{{ $vehicle->plat_nomor }}" required>
                    </div>

                    {{-- Tahun --}}
                    <div class="col-md-6">
                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="year" class="form-control"
                               value="{{ $vehicle->year }}" required>
                    </div>

                    {{-- Opsional --}}
                    <div class="col-md-6">
                        <label class="form-label">Warna</label>
                        <input type="text" name="warna" class="form-control"
                               value="{{ $vehicle->warna }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tipe</label>
                        <input type="text" name="tipe" class="form-control"
                               value="{{ $vehicle->tipe }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Pabrik</label>
                        <input type="text" name="factory" class="form-control"
                               value="{{ $vehicle->factory }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kapasitas Muatan (kg)</label>
                        <input type="number" name="load_capacity" class="form-control"
                               value="{{ $vehicle->load_capacity }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Berat (kg)</label>
                        <input type="number" name="weight" class="form-control"
                               value="{{ $vehicle->weight }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Bahan Bakar</label>
                        <input type="text" name="bahan_bakar" class="form-control"
                               value="{{ $vehicle->bahan_bakar }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control"
                               value="{{ $vehicle->lokasi }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jarak Tempuh (km)</label>
                        <input type="number" name="distance" class="form-control"
                               value="{{ $vehicle->distance }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">KM Terakhir Ganti Oli (km)</label>
                        <input type="number" name="last_km_for_oil" class="form-control"
                               value="{{ $vehicle->last_km_for_oil }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Interval Ganti Oli (km)</label>
                        <input type="number" name="oil_change_interval" class="form-control"
                               value="{{ $vehicle->oil_change_interval }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Persentase BBM (%)</label>
                        <input type="number" name="fuel_percent" class="form-control"
                               value="{{ $vehicle->fuel_percent }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $vehicle->notes }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Foto Kendaraan</label>
                        <input type="file" name="photo_path" class="form-control">
                        @if ($vehicle->photo_path)
                            <small class="text-muted">Foto saat ini: {{ $vehicle->photo_path }}</small>
                        @endif
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update</button>
                    </div>

                </form>
    </div>
</div>

@endsection
