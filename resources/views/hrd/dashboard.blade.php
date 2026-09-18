@extends('layouts.app')

@section('title', 'Dashboard HRD')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard HRD</h1>
        <p class="page-subtitle">Kelola kendaraan dan penugasan operasional</p>
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

{{-- Pending Assignments --}}
<div class="card mb-4">
    <div class="card-header">
        <h6 style="font-weight: 700; margin: 0;">
            Menunggu Penugasan
            @if($pendingAssign->count() > 0)
                <span class="badge-soft warning ms-2">{{ $pendingAssign->count() }}</span>
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
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingAssign as $pa)
                    <tr>
                        <td>
                            <a href="{{ route('borrowings.show', $pa->id) }}" style="font-weight: 600;">
                                {{ $pa->kode_pinjam }}
                            </a>
                        </td>
                        <td>{{ $pa->user->name ?? '-' }}</td>
                        <td>{{ Str::limit($pa->destination_address, 40) }}</td>
                        <td>{{ \Carbon\Carbon::parse($pa->start_at)->format('d/m/Y') }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $pa->id }}">
                                <i class="fa-solid fa-car"></i> Tugaskan
                            </button>
                        </td>
                    </tr>

                    {{-- Assign Modal --}}
                    <div class="modal fade" id="assignModal{{ $pa->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="font-weight: 700;">Tugaskan Kendaraan & Driver</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('borrowings.hrd-assign', $pa->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Pilih Kendaraan <span style="color: var(--danger);">*</span></label>
                                            <select name="vehicle_id" class="form-select" required>
                                                <option value="">-- Pilih Kendaraan --</option>
                                                @foreach(\App\Models\Vehicle::where('status', 'available')->get() as $veh)
                                                    <option value="{{ $veh->id }}">{{ $veh->name }} ({{ $veh->plat_nomor }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Pilih Driver <span class="text-muted">(opsional)</span></label>
                                            <select name="driver_id" class="form-select">
                                                <option value="">-- Tanpa Driver --</option>
                                                @foreach(\App\Models\Driver::where('status', 'aktif')->get() as $drv)
                                                    <option value="{{ $drv->id }}">{{ $drv->nama_driver }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Catatan HRD <span class="text-muted">(opsional)</span></label>
                                            <textarea name="hrd_notes" class="form-control" rows="2" placeholder="Tambahkan catatan..."></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="need_bod_approval" id="need_bod_{{ $pa->id }}" value="1">
                                                <label class="form-check-label" for="need_bod_{{ $pa->id }}">
                                                    Perlu Persetujuan BoD
                                                </label>
                                            </div>
                                            <small class="text-muted">Centang jika peminjaman ini memerlukan persetujuan Board of Directors</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Tugaskan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fa-solid fa-check-circle"></i>
                                <p>Tidak ada yang menunggu penugasan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Active Borrowings --}}
<div class="card">
    <div class="card-header">
        <h6 style="font-weight: 700; margin: 0;">Peminjaman Berlangsung</h6>
    </div>
    <div class="card-body p-0">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Kendaraan</th>
                    <th>Driver</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeBorrowings as $ab)
                    <tr>
                        <td>
                            <a href="{{ route('borrowings.show', $ab->id) }}" style="font-weight: 600;">
                                {{ $ab->kode_pinjam }}
                            </a>
                        </td>
                        <td>{{ $ab->user->name ?? '-' }}</td>
                        <td>{{ $ab->vehicle->name ?? '-' }}</td>
                        <td>{{ $ab->driver->nama_driver ?? '-' }}</td>
                        <td>
                            <a href="{{ route('inspections.create', $ab->id) }}" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-clipboard-check"></i> Inspeksi
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
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
