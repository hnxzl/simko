@extends('layouts.app')

@section('title', 'Detail Kendaraan')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">{{ $vehicle->name }}</h1>
        <p class="page-subtitle">{{ $vehicle->merk }} — {{ $vehicle->plat_nomor }}</p>
    </div>
    <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card" style="overflow: hidden;">
            <div style="height: 220px; background: var(--bg);">
                @if($vehicle->photo_path)
                    <img src="{{ asset('storage/'.$vehicle->photo_path) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Foto">
                @else
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-muted);">
                        <i class="fa-solid fa-car" style="font-size: 60px; opacity: 0.3;"></i>
                    </div>
                @endif
            </div>
        </div>

        {{-- Oil Change Alert --}}
        @if(in_array(strtolower(auth()->user()->role), ['admin', 'hrd']))
            @php
                $distanceSinceOilChange = $vehicle->distance - $vehicle->last_km_for_oil;
                $overKmInterval = $distanceSinceOilChange >= $vehicle->oil_change_interval;
                $overDateInterval = false;
                if ($lastOilChangeDate) {
                    $overDateInterval = \Carbon\Carbon::now()
                        ->greaterThanOrEqualTo(\Carbon\Carbon::parse($lastOilChangeDate)->addMonth());
                }
                $needOilChange = $overKmInterval || $overDateInterval;
            @endphp

            @if ($needOilChange)
                <div class="alert alert-danger mt-3 mb-0" style="font-size: 13px;">
                    <div class="d-flex align-items-start gap-2">
                        <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                        <div>
                            <strong>Perlu Ganti Oli!</strong>
                            @if ($overKmInterval)
                                <br>Kendaraan sudah menempuh <strong>{{ number_format($distanceSinceOilChange) }} km</strong> sejak ganti oli terakhir (interval: {{ number_format($vehicle->oil_change_interval) }} km).
                            @endif
                            @if ($overDateInterval)
                                <br>Sudah lebih dari <strong>1 bulan</strong> sejak ganti oli terakhir ({{ \Carbon\Carbon::parse($lastOilChangeDate)->format('d M Y') }}).
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 style="font-weight: 700; margin: 0;">Detail Kendaraan</h6>
                <div class="d-flex gap-2">
                    {{-- Status Badge --}}
                    @php
                        $statusBadge = match($vehicle->status) {
                            'available' => ['success', 'Tersedia'],
                            'in_use' => ['warning', 'Sedang Dipinjam'],
                            'maintenance' => ['danger', 'Dalam Perbaikan'],
                            default => ['secondary', ucfirst($vehicle->status)],
                        };
                    @endphp
                    <span class="badge-soft {{ $statusBadge[0] }}">{{ $statusBadge[1] }}</span>
                </div>
            </div>
            <div class="card-body">
                <table class="table-modern">
                    <tbody>
                        <tr><th>Kode Kendaraan</th><td>{{ $vehicle->kode_bmn ?? '-' }}</td></tr>
                        <tr><th>Nama</th><td>{{ $vehicle->name }}</td></tr>
                        <tr><th>Merk</th><td>{{ $vehicle->merk ?? '-' }}</td></tr>
                        <tr><th>Plat Nomor</th><td>{{ $vehicle->plat_nomor }}</td></tr>
                        <tr><th>Tipe</th><td>{{ $vehicle->tipe ?? '-' }}</td></tr>
                        <tr><th>Pabrik</th><td>{{ $vehicle->factory ?? '-' }}</td></tr>
                        <tr><th>Tahun</th><td>{{ $vehicle->year ?? '-' }}</td></tr>
                        <tr><th>Jarak Tempuh</th><td>{{ number_format($vehicle->distance ?? 0) }} km</td></tr>
                        <tr><th>Kapasitas Muatan</th><td>{{ number_format($vehicle->load_capacity ?? 0) }} kg</td></tr>
                        <tr><th>Berat</th><td>{{ number_format($vehicle->weight ?? 0) }} kg</td></tr>
                        <tr><th>Bahan Bakar</th><td>{{ $vehicle->bahan_bakar ?? '-' }}</td></tr>
                        <tr><th>Lokasi</th><td>{{ $vehicle->lokasi ?? '-' }}</td></tr>
                        <tr><th>Warna</th><td>{{ $vehicle->warna ?? '-' }}</td></tr>
                        <tr><th>KM Terakhir Ganti Oli</th><td>{{ number_format($vehicle->last_km_for_oil ?? 0) }} km</td></tr>
                        <tr><th>Tanggal Terakhir Ganti Oli</th><td>{{ $lastOilChangeDate ? \Carbon\Carbon::parse($lastOilChangeDate)->format('d M Y') : '-' }}</td></tr>
                        <tr><th>Interval Ganti Oli</th><td>{{ number_format($vehicle->oil_change_interval ?? 0) }} km</td></tr>
                        <tr><th>Persentase BBM</th><td>{{ $vehicle->fuel_percent ?? '-' }}%</td></tr>
                        <tr><th>Catatan</th><td>{{ $vehicle->notes ?? '-' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex gap-2 flex-wrap" style="margin-top: 16px;">
            {{-- Oil Change Button (Admin/HRD only) --}}
            @if(in_array(strtolower(auth()->user()->role), ['admin', 'hrd']))
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#oilChangeModal">
                    <i class="fa-solid fa-oil-can"></i> Ganti Oli
                </button>
            @endif

            {{-- Edit Button (Admin/HRD only) --}}
            @if(in_array(strtolower(auth()->user()->role), ['admin', 'hrd']))
                <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-primary">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
            @endif

            {{-- Maintenance Toggle (Admin/HRD only) --}}
            @if(in_array(strtolower(auth()->user()->role), ['admin', 'hrd']))
                @if($vehicle->status === 'available')
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                        <i class="fa-solid fa-wrench"></i>
                        <span class="d-none d-sm-inline ms-1">Dalam Perbaikan</span>
                        <i class="fa-solid fa-circle-info ms-1" style="font-size: 11px; opacity: 0.6;" title="Tandai kendaraan sedang perbaikan"></i>
                    </button>
                @elseif($vehicle->status === 'maintenance')
                    <form action="{{ route('vehicles.enable', $vehicle->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success">
                            <i class="fa-solid fa-check-circle"></i>
                            <span class="d-none d-sm-inline ms-1">Aktifkan Kembali</span>
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>

{{-- Oil Change Modal --}}
@if(in_array(strtolower(auth()->user()->role), ['admin', 'hrd']))
<div class="modal fade" id="oilChangeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 700;">Catat Pergantian Oli</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 13px; color: var(--text-secondary);">Kendaraan: <strong>{{ $vehicle->name }}</strong><br>Tanggal: <strong>{{ now()->format('d M Y') }}</strong></p>
                <form action="{{ route('oilchange.store', $vehicle->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">KM Saat Ganti Oli</label>
                        <input type="number" name="new_distance" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Maintenance Modal --}}
<div class="modal fade" id="maintenanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 700; color: var(--danger);">Tandai Dalam Perbaikan</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 13px;">Apakah kendaraan <strong>{{ $vehicle->name }}</strong> sedang dalam perbaikan? Kendaraan tidak akan bisa dipilih untuk peminjaman.</p>
                <form action="{{ route('vehicles.disable', $vehicle->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Alasan / Catatan Kerusakan <span class="text-muted">(opsional)</span></label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Penyok pada pintu kiri..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Tandai Dalam Perbaikan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
