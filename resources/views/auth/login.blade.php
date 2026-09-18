<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            background: url('{{ asset("img/bglog.jpg") }}') no-repeat center center fixed;
            background-size: cover;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 39, 68, 0.88) 0%, rgba(26, 111, 196, 0.75) 100%);
            z-index: 0;
        }

        /* Left panel — branding */
        .login-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px;
            position: relative;
            z-index: 1;
        }

        .login-left .logo-wrap {
            width: 90px; height: 90px;
            border-radius: 20px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
            padding: 10px;
        }

        .login-left .logo-wrap img {
            width: 70px; height: 70px;
            object-fit: contain;
        }

        .login-left h1 {
            font-size: 44px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -1px;
            margin-bottom: 12px;
        }

        .login-left p {
            color: rgba(255,255,255,0.8);
            font-size: 15px;
            text-align: center;
            max-width: 340px;
            line-height: 1.6;
        }

        .login-left .badge-app {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 99px;
            padding: 8px 18px;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            margin-top: 28px;
            backdrop-filter: blur(6px);
        }

        /* Right panel — form */
        .login-right {
            width: 480px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: rgba(255,255,255,0.97);
            border-radius: 20px;
            padding: 40px 36px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }

        .login-card .card-logo {
            width: 56px; height: 56px;
            border-radius: 14px;
            background: #f0f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            padding: 8px;
        }

        .login-card .card-logo img {
            width: 40px; height: 40px;
            object-fit: contain;
        }

        .login-card h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1a1d2e;
            text-align: center;
            margin-bottom: 4px;
        }

        .login-card .subtitle {
            font-size: 13px;
            color: #6b7294;
            text-align: center;
            margin-bottom: 28px;
        }

        .login-card label {
            font-size: 12.5px;
            font-weight: 600;
            color: #1a1d2e;
            margin-bottom: 5px;
            display: block;
        }

        .login-card .form-control {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 11px 14px;
            width: 100%;
            transition: all 0.2s;
            background: #fff;
        }

        .login-card .form-control:focus {
            border-color: #1a6fc4;
            box-shadow: 0 0 0 3px rgba(26,111,196,0.12);
            outline: none;
        }

        .login-card .btn-login {
            font-family: 'Inter', sans-serif;
            background: #1a6fc4;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .login-card .btn-login:hover { background: #145a9e; }

        /* Credential cards */
        .cred-section {
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid #e8ecf4;
        }

        .cred-section h4 {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ba3c2;
            margin-bottom: 12px;
            text-align: center;
        }

        .cred-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .cred-card {
            background: #f8fafc;
            border: 1.5px solid #e8ecf4;
            border-radius: 10px;
            padding: 10px 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .cred-card:hover {
            border-color: #1a6fc4;
            background: #e8f4fd;
        }

        .cred-card .cred-role {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 3px;
        }

        .cred-card .cred-email {
            font-size: 11px;
            color: #475569;
            word-break: break-all;
        }

        .cred-card .cred-pass {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .cred-card.admin .cred-role { color: #ef4444; }
        .cred-card.hrd .cred-role { color: #1a6fc4; }
        .cred-card.manager .cred-role { color: #f59e0b; }
        .cred-card.karyawan .cred-role { color: #10b981; }
        .cred-card.bod .cred-role { color: #06b6d4; }

        /* Responsive */
        @media (max-width: 900px) {
            body { flex-direction: column; }
            .login-left { display: none; }
            .login-right { width: 100%; padding: 24px; }
            .cred-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    {{-- Left Panel — Branding --}}
    <div class="login-left">
        <div class="logo-wrap">
            <img src="{{ asset('img/logo.png') }}" alt="Logo">
        </div>
        <h1>SIMKO</h1>
        <p>Sistem Informasi Manajemen Kendaraan Operasional — PT. Piranti Indonesia</p>
    </div>

    {{-- Right Panel — Form --}}
    <div class="login-right">
        <div class="login-card">
            <div class="card-logo">
                <img src="{{ asset('img/logo.png') }}" alt="Logo">
            </div>
            <h2>Selamat Datang</h2>
            <p class="subtitle">Masuk ke akun SIMKO Anda</p>

            <form action="{{ route('login.process') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="nama@perusahaan.com" required autofocus>
                    @error('email')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 8px;">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Masukkan password" required>
                    @error('password')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>&nbsp; Masuk
                </button>
            </form>

            {{-- Credential Info --}}
            <div class="cred-section">
                <h4><i class="fa-solid fa-key"></i> Demo Credentials (password: <strong>password</strong>)</h4>
                <div class="cred-grid">
                    <div class="cred-card admin" onclick="fillLogin('admin@simko.com')">
                        <div class="cred-role">Admin</div>
                        <div class="cred-email">admin@simko.com</div>
                        <div class="cred-pass">password</div>
                    </div>
                    <div class="cred-card hrd" onclick="fillLogin('afrisal.soelaiman@simko.com')">
                        <div class="cred-role">HRD</div>
                        <div class="cred-email">afrisal.soelaiman@simko.com</div>
                        <div class="cred-pass">password</div>
                    </div>
                    <div class="cred-card manager" onclick="fillLogin('dirman.sandewa@simko.com')">
                        <div class="cred-role">Manager</div>
                        <div class="cred-email">dirman.sandewa@simko.com</div>
                        <div class="cred-pass">password</div>
                    </div>
                    <div class="cred-card bod" onclick="fillLogin('djoko.irawan@simko.com')">
                        <div class="cred-role">BoD</div>
                        <div class="cred-email">djoko.irawan@simko.com</div>
                        <div class="cred-pass">password</div>
                    </div>
                    <div class="cred-card karyawan" onclick="fillLogin('adiansyah@simko.com')" style="grid-column: span 2;">
                        <div class="cred-role">Karyawan</div>
                        <div class="cred-email">adiansyah@simko.com</div>
                        <div class="cred-pass">password</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
            document.getElementById('password').focus();
        }
    </script>
</body>
</html>
