@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Detail Peminjaman</h1>
        <p class="page-subtitle">{{ $borrow->kode_pinjam }}</p>
    </div>
    <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-4">
    {{-- Main Info --}}
    <div class="col-lg-8">
        {{-- Stepper --}}
        <div class="card mb-4">
            <div class="card-body">
                @include('components.stepper', ['status' => $borrow->status])
            </div>
        </div>

        {{-- Detail Table --}}
        <div class="card">
            <div class="card-header">
                <h6 style="font-weight: 700; margin: 0;">Informasi Peminjaman</h6>
            </div>
            <div class="card-body">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px 0; width: 35%; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Kode Pinjam</td>
                        <td style="padding: 10px 0; font-weight: 600;">{{ $borrow->kode_pinjam }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Nama Peminjam</td>
                        <td style="padding: 10px 0;">{{ $borrow->user->name ?? '-' }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">NIP</td>
                        <td style="padding: 10px 0;">{{ $borrow->user->NIP ?? '-' }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Kendaraan</td>
                        <td style="padding: 10px 0;">
                            @if($borrow->vehicle)
                                {{ $borrow->vehicle->name }} <span class="text-muted">({{ $borrow->vehicle->plat_nomor }})</span>
                            @else
                                <span class="text-muted">Belum ditugaskan</span>
                            @endif
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Driver</td>
                        <td style="padding: 10px 0;">
                            @if($borrow->driver)
                                {{ $borrow->driver->nama_driver }}
                            @else
                                <span class="text-muted">Belum ditugaskan</span>
                            @endif
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Tujuan</td>
                        <td style="padding: 10px 0;">{{ $borrow->destination_address }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Keperluan</td>
                        <td style="padding: 10px 0;">{{ $borrow->purpose_text }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Tanggal Pergi</td>
                        <td style="padding: 10px 0;">{{ \Carbon\Carbon::parse($borrow->start_at)->format('d/m/Y') }} {{ $borrow->start_time ? '- ' . $borrow->start_time : '' }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Tanggal Pulang</td>
                        <td style="padding: 10px 0;">{{ \Carbon\Carbon::parse($borrow->end_at)->format('d/m/Y') }} {{ $borrow->end_time ? '- ' . $borrow->end_time : '' }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Status</td>
                        <td style="padding: 10px 0;">
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
                    </tr>

                    @if($borrow->manager_notes)
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Catatan Manager</td>
                        <td style="padding: 10px 0;">{{ $borrow->manager_notes }}</td>
                    </tr>
                    @endif

                    @if($borrow->hrd_notes)
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Catatan HRD</td>
                        <td style="padding: 10px 0;">{{ $borrow->hrd_notes }}</td>
                    </tr>
                    @endif

                    @if($borrow->bod_notes)
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Catatan BoD</td>
                        <td style="padding: 10px 0;">{{ $borrow->bod_notes }}</td>
                    </tr>
                    @endif

                    @if($borrow->isRejected() && $borrow->rejection_reason)
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--danger); font-size: 13px; font-weight: 500;">Alasan Penolakan</td>
                        <td style="padding: 10px 0; color: var(--danger); font-weight: 600;">{{ $borrow->rejection_reason }}</td>
                    </tr>
                    @endif

                    @if($borrow->surat_tugas_path)
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Surat Tugas</td>
                        <td style="padding: 10px 0;">
                            <a href="{{ route('borrowings.download', [$borrow->id, 'surat_tugas']) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-file-pdf"></i> Download Surat
                            </a>
                        </td>
                    </tr>
                    @endif

                    @if($borrow->lampiran_path)
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Lampiran</td>
                        <td style="padding: 10px 0;">
                            <a href="{{ route('borrowings.download', [$borrow->id, 'lampiran']) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-file-pdf"></i> Download Lampiran
                            </a>
                        </td>
                    </tr>
                    @endif

                    @if($borrow->inspection)
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; font-weight: 500;">Hasil Inspeksi</td>
                        <td style="padding: 10px 0;">
                            <a href="{{ route('inspections.show', $borrow->inspection->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-clipboard-check"></i> Lihat Hasil Inspeksi
                            </a>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Actions Sidebar --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 style="font-weight: 700; margin: 0;">Aksi</h6>
            </div>
            <div class="card-body d-flex flex-column gap-2">

                {{-- EDIT — hanya admin, manager, bod --}}
                @if(in_array(strtolower(auth()->user()->role), ['admin', 'manager', 'bod']) && $borrow->status === 'pending_manager')
                    <a href="{{ route('borrowings.edit', $borrow->id) }}" class="btn btn-primary w-100">
                        <i class="fa-solid fa-pen"></i> Edit Peminjaman
                    </a>
                @endif

                {{-- CANCEL — owner & not yet active --}}
                @if(auth()->id() === $borrow->user_id && in_array($borrow->status, ['pending_manager', 'pending_hrd', 'pending_bod', 'approved']))
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#CancelModal">
                        <i class="fa-solid fa-ban"></i> Batalkan
                    </button>
                @endif

                {{-- MANAGER APPROVE --}}
                @if(auth()->user()->canApprove() && $borrow->status === 'pending_manager')
                    <form id="managerApproveForm" action="{{ route('borrowings.manager-approve', $borrow->id) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label" style="font-size: 12px;">Catatan (opsional)</label>
                            <textarea name="manager_notes" class="form-control" rows="2" placeholder="Tambahkan catatan..."></textarea>
                        </div>
                        <button type="button" class="btn btn-success w-100" id="managerApproveBtn">
                            <i class="fa-solid fa-check"></i> Setujui
                        </button>
                    </form>
                    <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#RejectModal">
                        <i class="fa-solid fa-xmark"></i> Tolak
                    </button>
                @endif

                {{-- HRD ASSIGN --}}
                @if(auth()->user()->canAssign() && $borrow->status === 'pending_hrd')
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#HrdAssignModal">
                        <i class="fa-solid fa-car"></i> Tugaskan Kendaraan
                    </button>
                @endif

                {{-- BOD APPROVE --}}
                @if(auth()->user()->isBoD() && $borrow->status === 'pending_bod')
                    <button type="button" class="btn btn-success w-100" id="bodApproveBtn">
                        <i class="fa-solid fa-play"></i> Mulai Peminjaman
                    </button>
                @endif

                {{-- HRD INSPECTION --}}
                @if(auth()->user()->canAssign() && $borrow->status === 'active')
                    <a href="{{ route('inspections.create', $borrow->id) }}" class="btn btn-success w-100">
                        <i class="fa-solid fa-clipboard-check"></i> Inspeksi Kendaraan
                    </a>
                @endif

                @if(!auth()->id() || (auth()->id() !== $borrow->user_id && !auth()->user()->canApprove() && !auth()->user()->canAssign() && !auth()->user()->isBoD()))
                    <p class="text-muted text-center" style="font-size: 13px; margin: 0;">Tidak ada aksi tersedia</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="RejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 700; color: var(--danger);">Tolak Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('borrowings.manager-reject', $borrow->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p style="margin-bottom: 16px;">Apakah Anda yakin ingin menolak peminjaman ini?</p>
                    <div>
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span style="color: var(--danger);">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- HRD Assign Modal --}}
<div class="modal fade" id="HrdAssignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 700;">Tugaskan Kendaraan & Driver</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('borrowings.hrd-assign', $borrow->id) }}" method="POST">
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
                    <div>
                        <label class="form-label">Pilih Driver <span class="text-muted">(opsional)</span></label>
                        <select name="driver_id" class="form-select">
                            <option value="">-- Tanpa Driver --</option>
                            @foreach(\App\Models\Driver::where('status', 'aktif')->get() as $drv)
                                <option value="{{ $drv->id }}">{{ $drv->nama_driver }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Catatan HRD <span class="text-muted">(opsional)</span></label>
                        <textarea name="hrd_notes" class="form-control" rows="2" placeholder="Tambahkan catatan..."></textarea>
                    </div>
                    <div class="mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="need_bod_approval" id="need_bod_approval" value="1">
                            <label class="form-check-label" for="need_bod_approval">
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

{{-- Cancel Modal --}}
<div class="modal fade" id="CancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 700;">Batalkan Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin membatalkan peminjaman <strong>{{ $borrow->kode_pinjam }}</strong>?
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

{{-- Hidden BoD Approve Form --}}
<form id="bodApproveForm" action="{{ route('borrowings.bod-approve', $borrow->id) }}" method="POST" style="display:none;">
    @csrf
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Manager approve
    document.getElementById('managerApproveBtn')?.addEventListener('click', function() {
        Swal.fire({
            title: 'Persetujuan Manager',
            text: 'Setujui peminjaman {{ $borrow->kode_pinjam }}?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6b7294',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('managerApproveForm').submit();
            }
        });
    });

    // BoD approve
    document.getElementById('bodApproveBtn')?.addEventListener('click', function() {
        Swal.fire({
            title: 'Persetujuan BoD',
            text: 'Setujui dan mulai peminjaman {{ $borrow->kode_pinjam }}?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1a6fc4',
            cancelButtonColor: '#6b7294',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bodApproveForm').submit();
            }
        });
    });
</script>
@endpush

@endsection
