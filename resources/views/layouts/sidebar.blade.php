@php
    $role = strtolower(auth()->user()->role);
@endphp

<div class="sidebar" id="sidebar">
    {{-- Logo --}}
    <div class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
        <div class="logo-text">
            SIMKO
            <small>Vehicle Management</small>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="sidebar-nav">
        <div class="sidebar-label">Menu</div>

        <a href="{{ route($role.'.dashboard') }}"
           class="sidebar-link {{ Request::routeIs($role.'.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-grid-2"></i>
            <span>Dashboard</span>
        </a>

        {{-- ADMIN --}}
        @if($role === 'admin')
            <div class="sidebar-label">Kelola</div>
            <a href="{{ route('users.index') }}"
               class="sidebar-link {{ Request::routeIs('users.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                <span>Pengguna</span>
            </a>
            <a href="{{ route('vehicles.index') }}"
               class="sidebar-link {{ Request::routeIs('vehicles.*') ? 'active' : '' }}">
                <i class="fa-solid fa-car"></i>
                <span>Kendaraan</span>
            </a>
            <a href="{{ route('drivers.index') }}"
               class="sidebar-link {{ Request::routeIs('drivers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-id-card"></i>
                <span>Supir</span>
            </a>

            <div class="sidebar-label">Transaksi</div>
            <a href="{{ route('borrowings.index') }}"
               class="sidebar-link {{ Request::routeIs('borrowings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-handshake"></i>
                <span>Peminjaman</span>
            </a>
            <a href="{{ route('reports.borrow.form') }}"
               class="sidebar-link {{ Request::routeIs('reports.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-bar"></i>
                <span>Laporan</span>
            </a>
        @endif

        {{-- HRD --}}
        @if($role === 'hrd')
            <div class="sidebar-label">Kelola</div>
            <a href="{{ route('vehicles.index') }}"
               class="sidebar-link {{ Request::routeIs('vehicles.*') ? 'active' : '' }}">
                <i class="fa-solid fa-car"></i>
                <span>Kendaraan</span>
            </a>
            <a href="{{ route('drivers.index') }}"
               class="sidebar-link {{ Request::routeIs('drivers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-id-card"></i>
                <span>Supir</span>
            </a>

            <div class="sidebar-label">Transaksi</div>
            <a href="{{ route('borrowings.index') }}"
               class="sidebar-link {{ Request::routeIs('borrowings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-handshake"></i>
                <span>Peminjaman</span>
            </a>
            <a href="{{ route('reports.borrow.form') }}"
               class="sidebar-link {{ Request::routeIs('reports.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-bar"></i>
                <span>Laporan</span>
            </a>
        @endif

        {{-- KARYAWAN --}}
        @if($role === 'karyawan')
            <div class="sidebar-label">Layanan</div>
            <a href="{{ route('borrowings.index') }}"
               class="sidebar-link {{ Request::routeIs('borrowings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-handshake"></i>
                <span>Peminjaman</span>
            </a>
            <a href="{{ route('vehicles.index') }}"
               class="sidebar-link {{ Request::routeIs('vehicles.*') ? 'active' : '' }}">
                <i class="fa-solid fa-car"></i>
                <span>Kendaraan</span>
            </a>
        @endif

        {{-- MANAGER --}}
        @if($role === 'manager')
            <div class="sidebar-label">Layanan</div>
            <a href="{{ route('borrowings.index') }}"
               class="sidebar-link {{ Request::routeIs('borrowings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-handshake"></i>
                <span>Peminjaman</span>
            </a>
            <a href="{{ route('vehicles.index') }}"
               class="sidebar-link {{ Request::routeIs('vehicles.*') ? 'active' : '' }}">
                <i class="fa-solid fa-car"></i>
                <span>Kendaraan</span>
            </a>
        @endif

        {{-- BOD --}}
        @if($role === 'bod')
            <div class="sidebar-label">Layanan</div>
            <a href="{{ route('borrowings.index') }}"
               class="sidebar-link {{ Request::routeIs('borrowings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-handshake"></i>
                <span>Peminjaman</span>
            </a>
            <a href="{{ route('vehicles.index') }}"
               class="sidebar-link {{ Request::routeIs('vehicles.*') ? 'active' : '' }}">
                <i class="fa-solid fa-car"></i>
                <span>Kendaraan</span>
            </a>
            <a href="{{ route('reports.borrow.form') }}"
               class="sidebar-link {{ Request::routeIs('reports.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-bar"></i>
                <span>Laporan</span>
        </a>
        @endif
    </div>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        <a href="{{ route('logout') }}" class="sidebar-link" style="color: #fca5a5;">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Log Out</span>
        </a>
    </div>
</div>
