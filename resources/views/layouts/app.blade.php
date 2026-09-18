<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title')</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- SIMKO Design System --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @stack('styles')
</head>

<body>
    {{-- Sidebar Overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Main Content --}}
    <div class="content" id="mainContent">
        @include('layouts.header')

        <div class="page-content">
            @yield('content')
        </div>

        @include('layouts.footer')
    </div>

    {{-- Flatpickr --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('.datepicker', { dateFormat: 'Y-m-d', allowInput: true });
            flatpickr('.datetimepicker', { enableTime: true, dateFormat: 'Y-m-d H:i', allowInput: true });
        });
    </script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Setup CSRF token for all fetch requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Handle 419 errors on form submissions
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Check if session might be expired (form older than 7 hours)
                    const formAge = Date.now() - parseInt(form.dataset.loadedAt || Date.now());
                    if (formAge > 7 * 60 * 60 * 1000) { // 7 hours
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sesi Berakhir',
                            text: 'Sesi Anda telah berakhir. Silakan refresh halaman dan coba lagi.',
                            confirmButtonText: 'Refresh',
                            confirmButtonColor: '#1a6fc4'
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                });
                form.dataset.loadedAt = Date.now();
            });
        });

        @if(session('success'))
            Swal.fire({icon:'success', title:'Berhasil', html:'{{ session("success") }}', timer:2000, showConfirmButton:false, position:'center', toast:false});
        @endif
        @if(session('error'))
            Swal.fire({icon:'error', title:'Error', html:'{{ session("error") }}', timer:2000, showConfirmButton:false, position:'center', toast:false});
        @endif
        @if(session('info'))
            Swal.fire({icon:'info', title:'Info', html:'{{ session("info") }}', timer:2000, showConfirmButton:false, position:'center', toast:false});
        @endif

        function confirmDelete(formId) {
            Swal.fire({
                title: 'Yakin hapus?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Hapus'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }
    </script>

    {{-- Sidebar Toggle --}}
    <script>
        (function() {
            const toggler = document.getElementById('sidebarToggler');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (toggler) {
                toggler.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        // Mobile: slide in/out
                        sidebar.classList.toggle('show');
                        overlay.classList.toggle('show');
                    } else {
                        // Desktop: collapse/expand
                        sidebar.classList.toggle('collapsed');
                        document.body.classList.toggle('sidebar-collapsed');
                    }
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>
