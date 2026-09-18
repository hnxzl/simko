@extends('layouts.app')

@section('title', 'Inspeksi Kendaraan')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Inspeksi Kendaraan Kembali</h1>
        <p class="page-subtitle">Periksa kondisi kendaraan setelah peminjaman</p>
    </div>
    <a href="{{ route('borrowings.show', $borrow->id) }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h6 style="font-weight: 700; margin: 0;">Detail Peminjaman</h6>
            </div>
            <div class="card-body">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: var(--text-secondary); font-size: 13px; width: 40%;">Kode Pinjam</td>
                        <td style="padding: 8px 0; font-weight: 600;">{{ $borrow->kode_pinjam }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 8px 0; color: var(--text-secondary); font-size: 13px;">Peminjam</td>
                        <td style="padding: 8px 0;">{{ $borrow->user->name ?? '-' }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 8px 0; color: var(--text-secondary); font-size: 13px;">Kendaraan</td>
                        <td style="padding: 8px 0;">{{ $borrow->vehicle->name ?? '-' }} ({{ $borrow->vehicle->plat_nomor ?? '-' }})</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 8px 0; color: var(--text-secondary); font-size: 13px;">Driver</td>
                        <td style="padding: 8px 0;">{{ $borrow->driver->nama_driver ?? 'Self drive' }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 8px 0; color: var(--text-secondary); font-size: 13px;">Tujuan</td>
                        <td style="padding: 8px 0;">{{ $borrow->destination_address }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h6 style="font-weight: 700; margin: 0;">Form Inspeksi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('inspections.store', $borrow->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="fuel_level" class="form-label">Level BBM (%)</label>
                            <input type="number" name="fuel_level" id="fuel_level" class="form-control @error('fuel_level') is-invalid @enderror"
                                   min="0" max="100" value="{{ old('fuel_level') }}" placeholder="Contoh: 75">
                            @error('fuel_level')
                                <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="last_km" class="form-label">Kilometer Terakhir</label>
                            <input type="number" name="last_km" id="last_km" class="form-control @error('last_km') is-invalid @enderror"
                                   min="0" value="{{ old('last_km') }}" placeholder="Contoh: 50000">
                            @error('last_km')
                                <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="physical_condition_notes" class="form-label">Catatan Kondisi Fisik</label>
                            <textarea name="physical_condition_notes" id="physical_condition_notes" class="form-control @error('physical_condition_notes') is-invalid @enderror"
                                      rows="3" placeholder="Deskripsikan kondisi fisik kendaraan...">{{ old('physical_condition_notes') }}</textarea>
                            @error('physical_condition_notes')
                                <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apakah Ada Kerusakan?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_damaged" id="is_damaged_no" value="0" checked>
                                <label class="form-check-label" for="is_damaged_no">Tidak Ada Kerusakan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_damaged" id="is_damaged_yes" value="1">
                                <label class="form-check-label" for="is_damaged_yes" style="color: var(--danger);">Ada Kerusakan</label>
                            </div>
                        </div>
                        <div class="col-md-6" id="damagePhotoSection" style="display:none;">
                            <label for="damage_photos" class="form-label">Foto Kerusakan</label>
                            <input type="file" name="damage_photos" id="damage_photos" class="form-control @error('damage_photos') is-invalid @enderror" accept="image/*">
                            @error('damage_photos')
                                <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Hasil Inspeksi</button>
                        <a href="{{ route('borrowings.show', $borrow->id) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('input[name="is_damaged"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('damagePhotoSection').style.display = this.value === '1' ? 'block' : 'none';
        });
    });
</script>
@endpush

@endsection