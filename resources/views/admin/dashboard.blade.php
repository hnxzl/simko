@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Admin</h1>
        <p class="page-subtitle">Ringkasan sistem manajemen kendaraan operasional</p>
    </div>
</div>

{{-- Stat Cards Row 1 --}}
<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fa-solid fa-users"></i></div>
            <div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-label">Total Pengguna</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon info"><i class="fa-solid fa-user-tie"></i></div>
            <div>
                <div class="stat-value">{{ $totalKaryawan }}</div>
                <div class="stat-label">Karyawan</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon success"><i class="fa-solid fa-car-side"></i></div>
            <div>
                <div class="stat-value">{{ $kendaraanTersedia }}</div>
                <div class="stat-label">Kendaraan Tersedia</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon danger"><i class="fa-solid fa-wrench"></i></div>
            <div>
                <div class="stat-value">{{ $kendaraanPerbaikan }}</div>
                <div class="stat-label">Dalam Perbaikan</div>
            </div>
        </div>
    </div>
</div>

{{-- Stat Cards Row 2 --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon warning"><i class="fa-solid fa-location-arrow"></i></div>
            <div>
                <div class="stat-value">{{ $kendaraanOperasi }}</div>
                <div class="stat-label">Sedang Digunakan</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fa-solid fa-id-card"></i></div>
            <div>
                <div class="stat-value">{{ $totalDrivers }}</div>
                <div class="stat-label">Total Driver</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon success"><i class="fa-solid fa-user-check"></i></div>
            <div>
                <div class="stat-value">{{ $driversAktif }}</div>
                <div class="stat-label">Driver Aktif</div>
            </div>
        </div>
    </div>
</div>

{{-- Chart --}}
<div class="card mb-4">
    <div class="card-header">
        <h6 style="font-weight: 700; margin: 0;">Grafik Peminjaman</h6>
        <form method="GET" class="d-flex gap-2">
            <select name="chart_month" class="form-select form-select-sm" style="width: auto;">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $m == $filterMonth ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('M') }}
                    </option>
                @endforeach
            </select>
            <select name="chart_year" class="form-select form-select-sm" style="width: auto;">
                @for ($y = now()->year - 5; $y <= now()->year + 5; $y++)
                    <option value="{{ $y }}" {{ $y == $filterYear ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button class="btn btn-primary btn-sm">Filter</button>
        </form>
    </div>
    <div class="card-body">
        <canvas id="borrowChart" height="100"></canvas>
    </div>
</div>

{{-- Active Borrowings --}}
<div class="card">
    <div class="card-header">
        <h6 style="font-weight: 700; margin: 0;">Peminjaman Aktif</h6>
        <a href="{{ route('borrowings.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Kendaraan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjam as $p)
                    <tr>
                        <td>
                            <a href="{{ route('borrowings.show', $p->id) }}" style="font-weight: 600;">
                                {{ $p->kode_pinjam }}
                            </a>
                        </td>
                        <td>{{ $p->user->name ?? '-' }}</td>
                        <td>{{ $p->vehicle->name ?? 'Belum ditugaskan' }}</td>
                        <td>
                            @php
                                $badgeClass = match($p->status) {
                                    'active' => 'success',
                                    'pending_manager', 'pending_hrd' => 'warning',
                                    'approved' => 'info',
                                    'completed' => 'primary',
                                    'rejected' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge-soft {{ $badgeClass }}">{{ $p->status_label }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="fa-solid fa-inbox"></i>
                                <p>Tidak ada peminjaman aktif</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('borrowChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($chartDaily)) !!},
            datasets: [{
                label: 'Peminjaman',
                data: {!! json_encode(array_values($chartDaily)) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#6366f1'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f2f8' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
