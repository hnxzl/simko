@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Selamat Datang, {{ $user->name }}</h1>
        <p class="page-subtitle">Kelola peminjaman kendaraan operasional Anda</p>
    </div>
    <a href="{{ route('borrowings.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Ajukan Peminjaman
    </a>
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
            <div class="stat-icon primary"><i class="fa-solid fa-list"></i></div>
            <div>
                <div class="stat-value">{{ $myBorrowings->count() }}</div>
                <div class="stat-label">Total Pengajuan</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon warning"><i class="fa-solid fa-clock"></i></div>
            <div>
                <div class="stat-value">{{ $myBorrowings->whereIn('status', ['pending_manager', 'pending_hrd', 'approved'])->count() }}</div>
                <div class="stat-label">Menunggu Persetujuan</div>
            </div>
        </div>
    </div>
</div>

{{-- My Borrowings --}}
<div class="card">
    <div class="card-header">
        <h6 style="font-weight: 700; margin: 0;">Riwayat Peminjaman</h6>
        <a href="{{ route('borrowings.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Kendaraan</th>
                    <th>Tujuan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myBorrowings as $mb)
                    <tr>
                        <td>
                            <a href="{{ route('borrowings.show', $mb->id) }}" style="font-weight: 600;">
                                {{ $mb->kode_pinjam }}
                            </a>
                        </td>
                        <td>{{ $mb->vehicle->name ?? 'Belum ditugaskan' }}</td>
                        <td>{{ Str::limit($mb->destination_address, 30) }}</td>
                        <td>{{ \Carbon\Carbon::parse($mb->start_at)->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $badgeClass = match($mb->status) {
                                    'pending_manager', 'pending_hrd' => 'warning',
                                    'approved' => 'info',
                                    'active' => 'success',
                                    'completed' => 'primary',
                                    'rejected' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge-soft {{ $badgeClass }}">{{ $mb->status_label }}</span>
                        </td>
                        <td>
                            <a href="{{ route('borrowings.show', $mb->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            @if($mb->status === 'pending_manager' && $mb->user_id === auth()->id())
                                <a href="{{ route('borrowings.edit', $mb->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fa-solid fa-inbox"></i>
                                <p>Belum ada pengajuan peminjaman</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection