@extends('layouts.app')

@section('title', 'Dashboard BoD')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Board of Directors</h1>
        <p class="page-subtitle">Monitoring keseluruhan peminjaman kendaraan</p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon success"><i class="fa-solid fa-car-side"></i></div>
            <div>
                <div class="stat-value">{{ $kendaraanTersedia }}</div>
                <div class="stat-label">Kendaraan Tersedia</div>
            </div>
        </div>
    </div>
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
            <div class="stat-icon danger"><i class="fa-solid fa-wrench"></i></div>
            <div>
                <div class="stat-value">{{ $kendaraanPerbaikan }}</div>
                <div class="stat-label">Dalam Perbaikan</div>
            </div>
        </div>
    </div>
</div>

{{-- All Borrowings --}}
<div class="card">
    <div class="card-header">
        <h6 style="font-weight: 700; margin: 0;">Semua Peminjaman</h6>
    </div>
    <div class="card-body p-0">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Kendaraan</th>
                    <th>Driver</th>
                    <th>Tujuan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allBorrowings as $ab)
                    <tr>
                        <td>
                            <a href="{{ route('borrowings.show', $ab->id) }}" style="font-weight: 600;">
                                {{ $ab->kode_pinjam }}
                            </a>
                        </td>
                        <td>{{ $ab->user->name ?? '-' }}</td>
                        <td>{{ $ab->vehicle->name ?? 'Belum ditugaskan' }}</td>
                        <td>{{ $ab->driver->nama_driver ?? '-' }}</td>
                        <td>{{ Str::limit($ab->destination_address, 30) }}</td>
                        <td>{{ \Carbon\Carbon::parse($ab->start_at)->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $badgeClass = match($ab->status) {
                                    'pending_manager', 'pending_hrd' => 'warning',
                                    'approved' => 'info',
                                    'active' => 'success',
                                    'completed' => 'primary',
                                    'rejected' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge-soft {{ $badgeClass }}">{{ $ab->status_label }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fa-solid fa-inbox"></i>
                                <p>Belum ada data peminjaman</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($allBorrowings->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
            {{ $allBorrowings->links() }}
        </div>
    @endif
</div>

@endsection
