@extends('layouts.app')

@section('title', 'Detail Driver')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Driver</h1>
        <p class="page-subtitle">{{ $driver->nama_driver }}</p>
    </div>
    <div class="d-flex gap-2">
        @if(in_array(strtolower(auth()->user()->role), ['admin', 'hrd']))
        <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square me-2"></i>Ubah
        </a>
        @endif
        <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Profile Card --}}
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-4">
                <div style="width:72px;height:72px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-weight:700;font-size:28px">
                    {{ strtoupper(substr($driver->nama_driver, 0, 1)) }}
                </div>
                <h5 class="fw-semibold mb-1">{{ $driver->nama_driver }}</h5>
                @if($driver->status == 'aktif')
                    <span class="badge-soft success">Aktif</span>
                @elseif($driver->status == 'bertugas')
                    <span class="badge-soft warning">Bertugas</span>
                @else
                    <span class="badge-soft danger">Nonaktif</span>
                @endif
                <div class="mt-3 text-muted small">
                    Dibuat: {{ $driver->created_at ? $driver->created_at->format('d M Y') : '-' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Info --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0" style="color:#fff">Informasi Driver</h6>
            </div>
            <div class="card-body p-0">
                <table class="table-modern">
                    <tbody>
                        <tr>
                            <td style="width:200px;color:var(--text-secondary);font-size:13px;font-weight:500">Kode Driver</td>
                            <td class="fw-medium">{{ $driver->kode_driver ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="color:var(--text-secondary);font-size:13px;font-weight:500">NIK</td>
                            <td class="fw-medium">{{ $driver->nik ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="color:var(--text-secondary);font-size:13px;font-weight:500">No HP</td>
                            <td class="fw-medium">{{ $driver->no_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="color:var(--text-secondary);font-size:13px;font-weight:500">Jenis SIM</td>
                            <td>
                                @if($driver->jenis_sim)
                                    <span class="badge-soft info">{{ $driver->jenis_sim }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="color:var(--text-secondary);font-size:13px;font-weight:500">Alamat</td>
                            <td class="fw-medium">{{ $driver->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="color:var(--text-secondary);font-size:13px;font-weight:500">Status</td>
                            <td>
                                @if($driver->status == 'aktif')
                                    <span class="badge-soft success">Aktif</span>
                                @elseif($driver->status == 'bertugas')
                                    <span class="badge-soft warning">Bertugas</span>
                                @else
                                    <span class="badge-soft danger">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
