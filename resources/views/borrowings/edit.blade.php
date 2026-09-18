@extends('layouts.app')

@section('title', 'Edit Peminjaman')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Peminjaman</h1>
        <p class="page-subtitle">Perbarui detail pengajuan peminjaman {{ $borrow->kode_pinjam }}</p>
    </div>
    <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form action="{{ route('borrowings.update', $borrow->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kode Pinjam</label>
                    <input type="text" class="form-control" value="{{ $borrow->kode_pinjam }}" readonly style="background: var(--bg);">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    @php
                        $badgeClass = match($borrow->status) {
                            'pending_manager', 'pending_hrd' => 'warning',
                            'approved' => 'info',
                            'active' => 'success',
                            'completed' => 'primary',
                            'rejected' => 'danger',
                            default => 'secondary',
                        };
                    @endphp
                    <div style="padding-top: 8px;">
                        <span class="badge-soft {{ $badgeClass }}">{{ $borrow->status_label }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="start_at" class="form-label">Tanggal Pergi <span style="color: var(--danger);">*</span></label>
                    <input type="date" name="start_at" id="start_at"
                           class="form-control @error('start_at') is-invalid @enderror"
                           value="{{ old('start_at', \Carbon\Carbon::parse($borrow->start_at)->format('Y-m-d')) }}" required>
                    @error('start_at')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="end_at" class="form-label">Tanggal Pulang <span style="color: var(--danger);">*</span></label>
                    <input type="date" name="end_at" id="end_at"
                           class="form-control @error('end_at') is-invalid @enderror"
                           value="{{ old('end_at', \Carbon\Carbon::parse($borrow->end_at)->format('Y-m-d')) }}" required>
                    @error('end_at')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="start_time" class="form-label">Jam Pergi</label>
                    <input type="time" name="start_time" id="start_time"
                           class="form-control @error('start_time') is-invalid @enderror"
                           value="{{ old('start_time', $borrow->start_time) }}">
                    @error('start_time')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="end_time" class="form-label">Jam Pulang</label>
                    <input type="time" name="end_time" id="end_time"
                           class="form-control @error('end_time') is-invalid @enderror"
                           value="{{ old('end_time', $borrow->end_time) }}">
                    @error('end_time')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="destination_address" class="form-label">Tujuan <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="destination_address" id="destination_address"
                           value="{{ old('destination_address', $borrow->destination_address) }}"
                           class="form-control @error('destination_address') is-invalid @enderror" required>
                    @error('destination_address')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="purpose_text" class="form-label">Keperluan <span style="color: var(--danger);">*</span></label>
                    <textarea name="purpose_text" id="purpose_text" rows="3"
                              class="form-control @error('purpose_text') is-invalid @enderror" required>{{ old('purpose_text', $borrow->purpose_text) }}</textarea>
                    @error('purpose_text')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="alert alert-info" style="margin-top: 20px;">
                <i class="fa-solid fa-info-circle"></i>
                <span>Kendaraan dan driver akan tetap ditugaskan oleh HRD.</span>
            </div>

            <div class="d-flex gap-2" style="margin-top: 8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Perbarui
                </button>
                <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    flatpickr("#start_time", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true });
    flatpickr("#end_time", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true });
</script>
@endpush

@endsection
