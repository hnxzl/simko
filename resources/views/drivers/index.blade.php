@extends('layouts.app')

@section('title', 'Data Driver')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Data Driver</h1>
        <p class="page-subtitle">Kelola data driver dan informasi kontak</p>
    </div>
    <a href="{{ route('drivers.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Tambah Driver
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>Nama Driver</th>
                    <th>NIK</th>
                    <th>No HP</th>
                    <th>Jenis SIM</th>
                    <th>Status</th>
                    <th style="width:220px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $driver)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:600;font-size:13px">
                                    {{ strtoupper(substr($driver->nama_driver, 0, 1)) }}
                                </div>
                                <span class="fw-medium">{{ $driver->nama_driver }}</span>
                            </div>
                        </td>
                        <td>{{ $driver->nik ?? '-' }}</td>
                        <td>{{ $driver->no_hp ?? '-' }}</td>
                        <td>
                            @if($driver->jenis_sim)
                                <span class="badge-soft info">{{ $driver->jenis_sim }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($driver->status == 'aktif')
                                <span class="badge-soft success">Aktif</span>
                            @elseif($driver->status == 'bertugas')
                                <span class="badge-soft warning">Bertugas</span>
                            @else
                                <span class="badge-soft danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('drivers.show', $driver->id) }}"
                                   class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('drivers.edit', $driver->id) }}"
                                   class="btn btn-sm btn-outline-success" title="Ubah">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $driver->id }}"
                                        title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Delete Modal --}}
                    <div class="modal fade" id="deleteModal{{ $driver->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body text-center p-4">
                                    <div style="width:56px;height:56px;border-radius:50%;background:#fee2e2;color:#ef4444;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:24px">
                                        <i class="fa-solid fa-trash"></i>
                                    </div>
                                    <h5 class="mb-2">Hapus Driver?</h5>
                                    <p class="text-muted mb-3">
                                        Driver <strong>{{ $driver->nama_driver }}</strong> akan dihapus permanen.
                                    </p>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('drivers.destroy', $driver->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fa-solid fa-user-slash"></i>
                                <p>Belum ada data driver</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
