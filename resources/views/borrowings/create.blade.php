@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Ajukan Peminjaman</h1>
        <p class="page-subtitle">Isi form berikut untuk mengajukan peminjaman kendaraan</p>
    </div>
    <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form action="{{ route('borrowings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="start_at" class="form-label">Tanggal Pergi <span style="color: var(--danger);">*</span></label>
                    <input type="date" name="start_at" id="start_at" value="{{ old('start_at') }}"
                           class="form-control @error('start_at') is-invalid @enderror" required>
                    @error('start_at')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="end_at" class="form-label">Tanggal Pulang <span style="color: var(--danger);">*</span></label>
                    <input type="date" name="end_at" id="end_at" value="{{ old('end_at') }}"
                           class="form-control @error('end_at') is-invalid @enderror" required>
                    @error('end_at')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="start_time" class="form-label">Jam Pergi</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                           class="form-control @error('start_time') is-invalid @enderror">
                    @error('start_time')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="end_time" class="form-label">Jam Pulang</label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                           class="form-control @error('end_time') is-invalid @enderror">
                    @error('end_time')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="destination_address" class="form-label">Alamat Tujuan <span style="color: var(--danger);">*</span></label>
                    <textarea name="destination_address" id="destination_address" rows="2"
                              class="form-control @error('destination_address') is-invalid @enderror" required>{{ old('destination_address') }}</textarea>
                    @error('destination_address')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="purpose_text" class="form-label">Keperluan <span style="color: var(--danger);">*</span></label>
                    <textarea name="purpose_text" id="purpose_text" rows="2"
                              class="form-control @error('purpose_text') is-invalid @enderror" required>{{ old('purpose_text') }}</textarea>
                    @error('purpose_text')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="surat_tugas" class="form-label">Surat Tugas / Lampiran <span class="text-muted">(PDF, opsional)</span></label>
                    <input type="file" name="surat_tugas" id="surat_tugas"
                           class="form-control @error('surat_tugas') is-invalid @enderror" accept=".pdf">
                    @error('surat_tugas')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="alert alert-info" style="margin-top: 20px;">
                <i class="fa-solid fa-info-circle"></i>
                <span>Kendaraan dan driver akan ditugaskan oleh HRD setelah pengajuan disetujui Manager.</span>
            </div>

            <div class="d-flex gap-2" style="margin-top: 8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Ajukan Peminjaman
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