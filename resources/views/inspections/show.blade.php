@extends('layouts.app')

@section('title', 'Detail Inspeksi')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Hasil Inspeksi Kendaraan</h1>
        <p class="page-subtitle">{{ $inspection->borrowRequest->kode_pinjam }}</p>
    </div>
    <a href="{{ route('borrowings.show', $inspection->borrowRequest->id) }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h6 style="font-weight: 700; margin: 0;">Detail Peminjaman</h6></div>
            <div class="card-body">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: var(--text-secondary); font-size: 13px; width: 40%;">Kode Pinjam</td>
                        <td style="padding: 8px 0; font-weight: 600;">{{ $inspection->borrowRequest->kode_pinjam }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 8px 0; color: var(--text-secondary); font-size: 13px;">Peminjam</td>
                        <td style="padding: 8px 0;">{{ $inspection->borrowRequest->user->name ?? '-' }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 8px 0; color: var(--text-secondary); font-size: 13px;">Kendaraan</td>
                        <td style="padding: 8px 0;">{{ $inspection->borrowRequest->vehicle->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h6 style="font-weight: 700; margin: 0;">Hasil Inspeksi</h6></div>
            <div class="card-body">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; width: 35%;">Level BBM</td>
                        <td style="padding: 10px 0;">
                            @if($inspection->fuel_level !== null)
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 120px; height: 8px; background: var(--border); border-radius: 99px; overflow: hidden;">
                                        <div style="width: {{ $inspection->fuel_level }}%; height: 100%; border-radius: 99px;
                                            background: {{ $inspection->fuel_level > 50 ? 'var(--success)' : ($inspection->fuel_level > 20 ? 'var(--warning)' : 'var(--danger)') }};"></div>
                                    </div>
                                    <span style="font-weight: 600;">{{ $inspection->fuel_level }}%</span>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px;">Kilometer Terakhir</td>
                        <td style="padding: 10px 0; font-weight: 600;">{{ $inspection->last_km ? number_format($inspection->last_km, 0, ',', '.') . ' km' : '-' }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px;">Catatan Kondisi</td>
                        <td style="padding: 10px 0;">{{ $inspection->physical_condition_notes ?? '-' }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px;">Kerusakan</td>
                        <td style="padding: 10px 0;">
                            @if($inspection->is_damaged)
                                <span class="badge-soft danger">Ada Kerusakan</span>
                            @else
                                <span class="badge-soft success">Tidak Ada Kerusakan</span>
                            @endif
                        </td>
                    </tr>
                    @if($inspection->damage_photos)
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px;">Foto Kerusakan</td>
                        <td style="padding: 10px 0;">
                            <img src="{{ asset('storage/' . $inspection->damage_photos) }}" alt="Foto Kerusakan" style="max-width: 250px; border-radius: var(--radius);">
                        </td>
                    </tr>
                    @endif
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px;">Diperiksa Oleh</td>
                        <td style="padding: 10px 0;">{{ $inspection->inspector->name ?? '-' }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px;">Tanggal Inspeksi</td>
                        <td style="padding: 10px 0;">{{ $inspection->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection