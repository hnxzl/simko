@extends('layouts.app')

@section('title', 'Generate Laporan Peminjaman')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Peminjaman</h1>
        <p class="page-subtitle">Generate laporan peminjaman kendaraan operasional</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0" style="color:#fff"><i class="fa-solid fa-file-export me-2"></i>Parameter Laporan</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('reports.borrow.generate') }}" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-select">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <select name="year" class="form-select">
                        @for ($y = now()->year - 5; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Format File</label>
                    <select name="format" class="form-select">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="csv">CSV</option>
                        <option value="json">JSON</option>
                    </select>
                </div>
            </div>

            <hr class="my-3">

            <h6 class="fw-semibold mb-3">Pilih Kolom</h6>

            <div class="row g-2">
                @foreach([
                    'nip' => 'NIP Peminjam',
                    'nama' => 'Nama Peminjam',
                    'kendaraan' => 'Nama Kendaraan',
                    'start' => 'Tanggal & Jam Pergi',
                    'end' => 'Tanggal & Jam Pulang',
                    'tujuan' => 'Tujuan',
                    'keperluan' => 'Keperluan',
                    'status' => 'Status',
                    'fuel_before' => 'Bensin Sebelum',
                    'fuel_after' => 'Bensin Setelah',
                    'km_before' => 'Kilometer Sebelum',
                    'km_after' => 'Kilometer Setelah',
                    'kondisi' => 'Kondisi',
                    'catatan' => 'Catatan'
                ] as $col => $label)
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="columns[]" value="{{ $col }}" checked id="col_{{ $col }}">
                        <label class="form-check-label" for="col_{{ $col }}">{{ $label }}</label>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <button class="btn btn-primary px-4">
                    <i class="fa-solid fa-download me-2"></i>Download Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
