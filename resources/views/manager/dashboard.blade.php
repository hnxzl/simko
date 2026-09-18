@extends('layouts.app')

@section('title', 'Dashboard Manager')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Manager</h1>
        <p class="page-subtitle">Persetujuan dan monitoring peminjaman kendaraan</p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon success"><i class="fa-solid fa-car-side"></i></div>
            <div>
                <div class="stat-value">{{ $kendaraanTersedia }}</div>
                <div class="stat-label">Kendaraan Tersedia</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon warning"><i class="fa-solid fa-location-arrow"></i></div>
            <div>
                <div class="stat-value">{{ $kendaraanOperasi }}</div>
                <div class="stat-label">Sedang Digunakan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon danger"><i class="fa-solid fa-wrench"></i></div>
            <div>
                <div class="stat-value">{{ $kendaraanPerbaikan }}</div>
                <div class="stat-label">Dalam Perbaikan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fa-solid fa-hourglass-half"></i></div>
            <div>
                <div class="stat-value">{{ $pendingApprovals->count() }}</div>
                <div class="stat-label">Pending Approval</div>
            </div>
        </div>
    </div>
</div>

{{-- Pending Approvals --}}
<div class="card mb-4">
    <div class="card-header">
        <h6 style="font-weight: 700; margin: 0;">
            Menunggu Persetujuan
            @if($pendingApprovals->count() > 0)
                <span class="badge-soft warning ms-2">{{ $pendingApprovals->count() }}</span>
            @endif
        </h6>
    </div>
    <div class="card-body p-0">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Tujuan</th>
                    <th>Keperluan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingApprovals as $pa)
                    <tr>
                        <td>
                            <a href="{{ route('borrowings.show', $pa->id) }}" style="font-weight: 600;">
                                {{ $pa->kode_pinjam }}
                            </a>
                        </td>
                        <td>{{ $pa->user->name ?? '-' }}</td>
                        <td>{{ Str::limit($pa->destination_address, 30) }}</td>
                        <td>{{ Str::limit($pa->purpose_text, 30) }}</td>
                        <td>{{ \Carbon\Carbon::parse($pa->start_at)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('borrowings.show', $pa->id) }}" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-eye"></i> Review
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fa-solid fa-check-circle"></i>
                                <p>Tidak ada pengajuan menunggu persetujuan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Chart --}}
<div class="card">
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

@endsection