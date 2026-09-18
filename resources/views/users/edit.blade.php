@extends('layouts.app')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Pengguna</h1>
        <p class="page-subtitle">Perbarui data {{ $user->name }}</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')

                    {{-- NIP --}}
                    <div class="col-md-6">
                        <label class="form-label w-100 text-start">NIP<span class="text-danger">*</span></label>
                        <input type="number" name="NIP" id="NIP"
                               value="{{ old('NIP', $user->NIP) }}"
                               class="form-control @error('NIP') is-invalid @enderror"
                               min="1" @required(true)>
                        @error('NIP')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div class="col-md-6">
                        <label class="form-label w-100 text-start">Nama<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $user->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               autocomplete="off" @required(true)>
                        @error('name')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label w-100 text-start">Email<span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $user->email) }}"
                               class="form-control @error('email') is-invalid @enderror"
                               autocomplete="off" @required(true)>
                        @error('email')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- Phone (Optional) --}}
                    <div class="col-md-6">
                        <label class="form-label w-100 text-start">Phone Number</label>
                        <input type="tel" name="phone" id="phone"
                               value="{{ old('phone', $user->phone) }}"
                               class="form-control @error('phone') is-invalid @enderror"
                               autocomplete="off">
                        @error('phone')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- Password (opsional) --}}
                    <div class="col-12">
                        <label class="form-label w-100 text-start">Sandi (opsional)</label>
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password (opsional) --}}
                    <div class="col-12">
                        <label class="form-label w-100 text-start">Konfirmasi Sandi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control @error('password_confirmation') is-invalid @enderror">
                        @error('password_confirmation')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- Role / Role --}}
                    <div class="col-md-4">
                        <label class="form-label w-100 text-start">Role<span class="text-danger">*</span></label>
                        <select id="Role" name="role" class="form-select" @required(true)>
                            @php $currentRole = old('role', $user->role); @endphp
                            <option value="karyawan" {{ $currentRole == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                            <option value="admin" {{ $currentRole == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="hrd" {{ $currentRole == 'hrd' ? 'selected' : '' }}>HRD</option>
                            <option value="manager" {{ $currentRole == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="bod" {{ $currentRole == 'bod' ? 'selected' : '' }}>Board of Directors</option>
                        </select>
                        @error('role')
                            <div class="text-danger text-start"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update</button>
                    </div>

                </form>
    </div>
</div>
@endsection
