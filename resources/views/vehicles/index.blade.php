@extends('layouts.app')

@section('title', 'Daftar Kendaraan')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Kendaraan</h1>
        <p class="page-subtitle">Daftar kendaraan operasional</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('borrowings.create') }}" class="btn btn-outline-primary"><i class="fa-solid fa-key"></i> Pinjam</a>
        @if(in_array(strtolower(auth()->user()->role), ['admin', 'hrd']))
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah</a>
        @endif
    </div>
</div>

{{-- Status Filter (admin/hrd only) --}}
@if(in_array(strtolower(auth()->user()->role), ['admin', 'hrd']))
<div class="card mb-4">
    <div class="card-body" style="padding: 14px 20px;">
        <form method="GET" action="{{ route('vehicles.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
            <select name="status" class="form-select" style="width: auto; min-width: 180px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                <option value="in_use" {{ request('status') === 'in_use' ? 'selected' : '' }}>Sedang Dipinjam</option>
                <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Dalam Perbaikan</option>
            </select>
        </form>
    </div>
</div>
@endif

<div class="row g-3">
        @forelse($vehicles as $vehicle)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100" style="overflow: hidden; {{ in_array($vehicle->status, ['in_use', 'maintenance']) ? 'opacity: 0.7;' : '' }}">
                <div style="height: 160px; overflow: hidden; background: var(--bg); position: relative;">
                    @if($vehicle->photo_path)
                        <img src="{{ asset('storage/' . $vehicle->photo_path) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Foto">
                    @else
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-muted);">
                            <i class="fa-solid fa-car" style="font-size: 40px; opacity: 0.3;"></i>
                        </div>
                    @endif
                    @if(in_array($vehicle->status, ['in_use', 'maintenance']))
                        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center;">
                            <span class="badge-soft {{ $vehicle->status === 'in_use' ? 'warning' : 'danger' }}" style="font-size: 12px;">
                                <i class="fa-solid {{ $vehicle->status === 'in_use' ? 'fa-car' : 'fa-wrench' }}"></i>
                                {{ $vehicle->status === 'in_use' ? 'Sedang Dipinjam' : 'Perbaikan' }}
                            </span>
                        </div>
                    @endif
                </div>
                <div style="padding: 16px;">
                    <h6 style="font-weight: 700; margin-bottom: 4px; font-size: 14px;">{{ $vehicle->name }}</h6>
                    <div style="font-size: 12px; color: var(--text-secondary);">{{ $vehicle->merk }}</div>
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">{{ $vehicle->plat_nomor }}</div>
                    <div style="margin-top: 8px;">
                        @php
                            $vStatus = match($vehicle->status) {
                                'available' => ['success', 'Tersedia'],
                                'in_use' => ['warning', 'Sedang Dipinjam'],
                                'maintenance' => ['danger', 'Dalam Perbaikan'],
                                default => ['secondary', ucfirst($vehicle->status)],
                            };
                        @endphp
                        <span class="badge-soft {{ $vStatus[0] }}">{{ $vStatus[1] }}</span>
                    </div>
                    <div class="d-flex gap-1" style="margin-top: 12px;">
                        <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-sm btn-outline-primary" style="flex: 1;"><i class="fa-solid fa-eye"></i> Detail</a>
                        @if(in_array(strtolower(auth()->user()->role), ['admin', 'hrd']))
                            <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-pen"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#Hapus{{ $vehicle->id }}" style="color: var(--danger);"><i class="fa-solid fa-trash"></i></button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Hapus --}}
        <div class="modal fade" id="Hapus{{ $vehicle->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Kendaraan</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus kendaraan ini?
                        <br><b>{{ $vehicle->name }} - {{ $vehicle->plat_nomor }}</b>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger">Hapus</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                    </div>
                </form>
            </div>
        </div>

        @empty
        <div class="col-12">
            <div class="empty-state" style="padding: 60px 24px;">
                <i class="fa-solid fa-car"></i>
                <p>Data kendaraan kosong</p>
            </div>
        </div>
        @endforelse
    </div>

@endsection

