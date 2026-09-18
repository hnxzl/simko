@extends('layouts.app')

@section('title', 'Profil')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Profil Saya</h1>
        <p class="page-subtitle">Kelola informasi akun Anda</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h6 style="font-weight: 700; margin: 0;">Informasi Akun</h6></div>
            <div class="card-body">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px; width: 35%;">Nama</td>
                        <td style="padding: 10px 0; font-weight: 600;">{{ $user->name }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px;">NIP</td>
                        <td style="padding: 10px 0;">{{ $user->NIP }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px;">Email</td>
                        <td style="padding: 10px 0;">{{ $user->email }}</td>
                    </tr>
                    <tr style="border-top: 1px solid var(--border-light);">
                        <td style="padding: 10px 0; color: var(--text-secondary); font-size: 13px;">Peran</td>
                        <td style="padding: 10px 0;">
                            <span class="badge-soft primary">{{ $user->role_label }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h6 style="font-weight: 700; margin: 0;">Pengaturan</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <hr style="border-color: var(--border); margin: 20px 0;">

                    <h6 style="font-weight: 700; margin-bottom: 16px;">Ganti Password</h6>

                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="old_password" class="form-control">
                        @error('old_password')
                            <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control">
                        @error('password')
                            <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
