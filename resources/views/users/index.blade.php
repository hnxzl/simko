@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Data Pengguna</h1>
        <p class="page-subtitle">Kelola pengguna sistem SIMKO</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Pengguna
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div style="overflow-x: auto;">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ ($users->currentPage()-1) * $users->perPage() + $loop->iteration }}</td>
                            <td>{{ $user->NIP }}</td>
                            <td style="font-weight: 600;">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                @php
                                    $roleBadge = match(strtolower($user->role)) {
                                        'admin' => 'danger',
                                        'hrd' => 'primary',
                                        'manager' => 'warning',
                                        'bod' => 'info',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge-soft {{ $roleBadge }}">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#Hapus{{ $user->id }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="Hapus{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" style="font-weight: 700;">Hapus Pengguna</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Yakin ingin menghapus pengguna ini?<br>
                                        <strong>{{ $user->NIP }} - {{ $user->name }}</strong>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fa-solid fa-inbox"></i>
                                    <p>Data pengguna kosong</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid var(--border);">
            {!! $users->links() !!}
        </div>
    @endif
</div>

@endsection
