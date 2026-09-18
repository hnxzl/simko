@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Peminjaman Kendaraan</h1>
        <p class="page-subtitle">Kelola semua pengajuan peminjaman kendaraan operasional</p>
    </div>
    <div class="d-flex gap-2">
        @if(in_array(strtolower(auth()->user()->role), ['admin', 'manager', 'bod']))
        <a href="{{ route('borrowings.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Ajukan Baru
        </a>
        @endif
        <a href="{{ route('reports.borrow.form') }}" class="btn btn-outline-primary">
            <i class="fa-solid fa-file-export"></i> Laporan
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body" style="padding: 14px 20px;">
        <form method="GET" action="{{ route('borrowings.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
            <select name="status" class="form-select" style="width: auto; min-width: 180px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending_manager" {{ request('status') === 'pending_manager' ? 'selected' : '' }}>Menunggu Manager</option>
                <option value="pending_hrd" {{ request('status') === 'pending_hrd' ? 'selected' : '' }}>Menunggu HRD</option>
                <option value="pending_bod" {{ request('status') === 'pending_bod' ? 'selected' : '' }}>Menunggu BoD</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <div class="d-flex gap-1">
                <input type="text" name="nama" class="form-control" placeholder="Cari nama..." value="{{ request('nama') }}" style="width: 180px;">
                <button class="btn btn-outline-primary">Cari</button>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <div style="overflow-x: auto;">
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings as $borrow)
                        <tr>
                            <td>
                                <a href="{{ route('borrowings.show', $borrow->id) }}" style="font-weight: 600;">
                                    {{ $borrow->kode_pinjam }}
                                </a>
                            </td>
                            <td>{{ $borrow->user->name ?? '-' }}</td>
                            <td>{{ $borrow->vehicle->name ?? '-' }}</td>
                            <td>{{ $borrow->driver->nama_driver ?? '-' }}</td>
                            <td>{{ Str::limit($borrow->destination_address, 25) }}</td>
                            <td>{{ \Carbon\Carbon::parse($borrow->start_at)->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $badgeClass = match($borrow->status) {
                                        'pending_manager', 'pending_hrd', 'pending_bod' => 'warning',
                                        'approved' => 'info',
                                        'active' => 'success',
                                        'completed' => 'primary',
                                        'rejected' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge-soft {{ $badgeClass }}">{{ $borrow->status_label }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('borrowings.show', $borrow->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>

                                    @if(auth()->id() === $borrow->user_id && in_array($borrow->status, ['pending_manager', 'pending_hrd', 'pending_bod', 'approved']))
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#Cancel{{ $borrow->id }}">Batal</button>
                                    @endif

                                    @if(auth()->user()->canApprove() && $borrow->status === 'pending_manager')
                                        <a href="{{ route('borrowings.show', $borrow->id) }}" class="btn btn-sm btn-success">Review</a>
                                    @endif

                                    @if(auth()->user()->canAssign() && $borrow->status === 'pending_hrd')
                                        <a href="{{ route('borrowings.show', $borrow->id) }}" class="btn btn-sm btn-primary">Assign</a>
                                    @endif

                                    @if(auth()->user()->canAssign() && $borrow->status === 'active')
                                        <a href="{{ route('inspections.create', $borrow->id) }}" class="btn btn-sm btn-success">Inspeksi</a>
                                    @endif

                                    @if(auth()->user()->isBoD() && $borrow->status === 'pending_bod')
                                        <a href="{{ route('borrowings.show', $borrow->id) }}" class="btn btn-sm btn-success">Review BoD</a>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Cancel Modal --}}
                        <div class="modal fade" id="Cancel{{ $borrow->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" style="font-weight: 700;">Batalkan Peminjaman</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Batalkan peminjaman <strong>{{ $borrow->kode_pinjam }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                                        <form action="{{ route('borrowings.cancel', $borrow->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fa-solid fa-inbox"></i>
                                    <p>Tidak ada data peminjaman</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($borrowings->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
            {!! $borrowings->links() !!}
        </div>
    @endif
</div>

@endsection
