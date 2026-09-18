@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Notifikasi</h1>
        <p class="page-subtitle">Pemberitahuan dan pengingat sistem</p>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Notifikasi</th>
                    <th style="width:120px">Tipe</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center">
                                <i class="fa-solid fa-bell" style="font-size:14px"></i>
                            </div>
                            <span>Pengajuan peminjaman baru masuk</span>
                        </div>
                    </td>
                    <td><span class="badge-soft info">Baru</span></td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:50%;background:#fef3c7;color:#f59e0b;display:flex;align-items:center;justify-content:center">
                                <i class="fa-solid fa-clock" style="font-size:14px"></i>
                            </div>
                            <span>Jadwal pengecekan hari ini</span>
                        </div>
                    </td>
                    <td><span class="badge-soft warning">Reminder</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
