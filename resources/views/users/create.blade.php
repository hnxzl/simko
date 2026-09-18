@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Pengguna</h1>
        <p class="page-subtitle">Buat akun pengguna baru</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label for="NIP" class="form-label w-100 text-start">NIP<span class="text-danger">*</span></label>
                        <input type="number" value="{{ @old('NIP') }}" name="NIP" class="form-control @error('NIP') is-invalid @enderror" id="NIP" min="1" @required(true)>
                        @error('NIP')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label w-100 text-start">Nama<span class="text-danger">*</span></label>
                        <input type="text" value="{{ @old('name') }}" name="name" class="form-control @error('name') is-invalid @enderror" id="name" autocomplete="off" @required(true)>
                        @error('name')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label w-100 text-start">Email<span class="text-danger">*</span></label>
                        <input type="email" value="{{ @old('email') }}" name="email" class="form-control @error('email') is-invalid @enderror" id="email" autocomplete="off" @required(true)>
                        @error('email')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- Phone (Optional) --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label w-100 text-start">Phone Number</label>
                        <input type="tel" value="{{ @old('phone') }}" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" autocomplete="off">
                        @error('phone')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="password" class="form-label w-100 text-start">Sandi<span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" @required(true)>
                        @error('password')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="col-12">
                        <label for="password_confirmation" class="form-label w-100 text-start">Konfirmasi Sandi<span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" @required(true)>
                        @error('password_confirmation')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="Role" class="form-label w-100 text-start">Role<span class="text-danger">*</span></label>
                        <select id="Role" name="role" class="form-select" @required(true)>
                            <option value="karyawan" selected>Karyawan</option>
                            <option value="admin">Admin</option>
                            <option value="hrd">HRD</option>
                            <option value="manager">Manager</option>
                            <option value="bod">Board of Directors</option>
                        </select>
                        @error('role')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button>
                    </div>

                </form>
    </div>
</div>
@endsection
