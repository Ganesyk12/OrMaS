<!doctype html>
<html lang="id">

<head>
  <title>Masuk | {{ config('app.name') }}</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="Masuk ke Sistem Administrasi Organisasi" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="icon" href="{{ asset('berry-admin-template/dist/assets/images/favicon.svg') }}" type="image/x-icon" />

  @include('layouts.partials.head-css')

  <style>
    body.auth-page {
      min-height: 100vh;
      background: #f3f7fb;
      color: #1f2937;
    }

    .auth-shell {
      display: grid;
      grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
      min-height: 100vh;
    }

    /* Panel branding kiri */
    .auth-visual {
      position: relative;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      padding: 48px;
      color: #fff;
      background:
        radial-gradient(circle at 85% 15%, rgba(56, 189, 248, 0.22), transparent 32%),
        radial-gradient(circle at 10% 90%, rgba(59, 130, 246, 0.28), transparent 38%),
        linear-gradient(145deg, #0e243d 0%, #153a5f 52%, #1b5b99 100%);
    }

    .auth-visual::before,
    .auth-visual::after {
      content: '';
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
    }

    .auth-visual::before {
      width: 420px;
      height: 420px;
      top: -180px;
      right: -140px;
      border: 1px solid rgba(255, 255, 255, 0.12);
      box-shadow: 0 0 0 60px rgba(255, 255, 255, 0.025), 0 0 0 120px rgba(255, 255, 255, 0.02);
    }

    .auth-visual::after {
      width: 260px;
      height: 260px;
      bottom: -120px;
      left: -100px;
      background: rgba(56, 189, 248, 0.1);
      filter: blur(2px);
    }

    .auth-brand {
      position: relative;
      z-index: 1;
      display: inline-flex;
      align-items: center;
      gap: 12px;
      color: #fff;
      text-decoration: none;
      font-size: 1.05rem;
      font-weight: 700;
      letter-spacing: 0.01em;
    }

    .auth-brand:hover {
      color: #fff;
    }

    .auth-brand .brand-mark {
      display: grid;
      place-items: center;
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.18);
      font-size: 22px;
    }

    .auth-visual-body {
      position: relative;
      z-index: 1;
      max-width: 540px;
      margin: auto 0;
      padding: 48px 0;
    }

    .auth-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 20px;
      padding: 7px 12px;
      border: 1px solid rgba(255, 255, 255, 0.16);
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.08);
      color: rgba(255, 255, 255, 0.82);
      font-size: 0.78rem;
      font-weight: 500;
      letter-spacing: 0.04em;
      text-transform: uppercase;
    }

    .auth-visual-body h1 {
      margin-bottom: 18px;
      color: #fff;
      font-size: clamp(2rem, 3.1vw, 3rem);
      font-weight: 700;
      line-height: 1.15;
      letter-spacing: -0.025em;
    }

    .auth-visual-body>p {
      max-width: 460px;
      margin-bottom: 0;
      color: rgba(255, 255, 255, 0.72);
      font-size: 1rem;
      line-height: 1.75;
    }

    .auth-features {
      display: grid;
      gap: 14px;
      margin: 32px 0 0;
      padding: 0;
      list-style: none;
    }

    .auth-features li {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      color: rgba(255, 255, 255, 0.86);
      font-size: 0.93rem;
    }

    .auth-features li i {
      display: grid;
      place-items: center;
      flex-shrink: 0;
      width: 24px;
      height: 24px;
      margin-top: 1px;
      border-radius: 50%;
      background: rgba(125, 211, 252, 0.16);
      color: #7dd3fc;
      font-size: 15px;
    }

    .auth-visual-footer {
      position: relative;
      z-index: 1;
      color: rgba(255, 255, 255, 0.48);
      font-size: 0.78rem;
    }

    /* Form login kanan */
    .auth-form-side {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 48px 32px;
    }

    .auth-card {
      width: 100%;
      max-width: 440px;
    }

    .auth-mobile-brand {
      display: none;
      align-items: center;
      gap: 10px;
      margin-bottom: 34px;
      color: #153a5f;
      text-decoration: none;
      font-size: 1.05rem;
      font-weight: 700;
    }

    .auth-mobile-brand .brand-mark {
      display: grid;
      place-items: center;
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: #e3f1fd;
      color: #2196f3;
      font-size: 20px;
    }

    .auth-card-head h2 {
      margin-bottom: 8px;
      color: #0f172a;
      font-size: 1.75rem;
      font-weight: 700;
      letter-spacing: -0.02em;
    }

    .auth-card-head p {
      margin-bottom: 28px;
      color: #6b7280;
      font-size: 0.94rem;
      line-height: 1.6;
    }

    .auth-card .form-label {
      margin-bottom: 7px;
      color: #374151;
      font-size: 0.875rem;
      font-weight: 600;
    }

    .auth-input {
      position: relative;
    }

    .auth-input>i {
      position: absolute;
      top: 50%;
      left: 14px;
      z-index: 2;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 18px;
      pointer-events: none;
    }

    .auth-input .form-control {
      height: 48px;
      padding: 0 14px 0 44px;
      border: 1px solid #d7e0ea;
      border-radius: 10px;
      background: #fff;
      color: #0f172a;
      font-size: 0.95rem;
      transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .auth-input .form-control:hover {
      background: #fff;
    }

    .auth-input .form-control:focus {
      border-color: #2196f3;
      box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.12);
    }

    .auth-input.auth-input-password .form-control {
      padding-right: 48px;
    }

    .auth-password-toggle {
      position: absolute;
      top: 50%;
      right: 6px;
      display: grid;
      place-items: center;
      width: 36px;
      height: 36px;
      transform: translateY(-50%);
      border: 0;
      border-radius: 8px;
      background: transparent;
      color: #94a3b8;
      cursor: pointer;
    }

    .auth-password-toggle:hover {
      background: #eff6ff;
      color: #2196f3;
    }

    .auth-hint {
      color: #94a3b8;
      font-size: 0.78rem;
    }

    .auth-submit {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      height: 48px;
      border-radius: 10px;
      font-weight: 600;
      box-shadow: 0 10px 20px -10px rgba(33, 150, 243, 0.65);
    }

    .auth-submit i {
      font-size: 18px;
    }

    .auth-divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 24px 0;
      color: #94a3b8;
      font-size: 0.78rem;
    }

    .auth-divider::before,
    .auth-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #e5eaf0;
    }

    .auth-back {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      height: 44px;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      background: #fff;
      color: #475569;
      font-weight: 500;
    }

    .auth-back:hover {
      background: #f8fafc;
      color: #1f2937;
    }

    .auth-alert {
      margin-bottom: 20px;
      border-radius: 10px;
      font-size: 0.86rem;
    }

    .auth-alert ul {
      margin-bottom: 0;
    }

    .auth-copyright {
      margin-top: 32px;
      color: #94a3b8;
      font-size: 0.78rem;
      text-align: center;
    }

    @media (max-width: 991.98px) {
      .auth-shell {
        grid-template-columns: 1fr;
      }

      .auth-visual {
        display: none;
      }

      .auth-form-side {
        justify-content: flex-start;
        min-height: 100vh;
        padding: 48px 20px 28px;
      }

      .auth-mobile-brand {
        display: inline-flex;
      }
    }

    @media (max-width: 575.98px) {
      .auth-form-side {
        padding: 32px 18px 24px;
      }

      .auth-card-head h2 {
        font-size: 1.5rem;
      }

      .auth-card-head p {
        margin-bottom: 22px;
      }
    }
  </style>
</head>

<body class="pc-default auth-page">
  <main class="auth-shell">
    <section class="auth-visual" aria-hidden="true">
      <a href="#" class="auth-brand">
        <span class="brand-mark"><i class="ti ti-affiliate"></i></span>
        <span>{{ config('app.name') }}</span>
      </a>

      <div class="auth-visual-body">
        <span class="auth-eyebrow"><i class="ti ti-sparkles"></i> Sistem Administrasi</span>
        <h1>Kelola organisasi lebih rapi, cepat, dan terpusat.</h1>
        <p>Satu tempat untuk mengelola data organisasi, anggota, kegiatan, rapat, keuangan, dan surat menyurat.</p>

        <ul class="auth-features">
          <li><i class="ti ti-check"></i> Data organisasi dan anggota yang terstruktur</li>
          <li><i class="ti ti-check"></i> Jadwal kegiatan dan rapat yang mudah dipantau</li>
          <li><i class="ti ti-check"></i> Pencatatan keuangan serta surat masuk dan keluar</li>
        </ul>
      </div>

      <div class="auth-visual-footer">
        &copy; {{ date('Y') }} {{ config('app.name') }}. Seluruh hak cipta dilindungi.
      </div>
    </section>

    <section class="auth-form-side">
      <div class="auth-card">
        <a href="{{ route('welcome') }}" class="auth-mobile-brand">
          <span class="brand-mark"><i class="ti ti-affiliate"></i></span>
          <span>{{ config('app.name') }}</span>
        </a>

        <div class="auth-card-head">
          <h2>Masuk ke akun Anda</h2>
          <p>Silakan masukkan email dan password untuk melanjutkan ke dashboard.</p>
        </div>

        @if (session('status'))
        <div class="alert alert-success auth-alert" role="alert">
          {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger auth-alert" role="alert">
          <div class="fw-semibold mb-1">Login gagal</div>
          <ul class="ps-3">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
          @csrf

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="auth-input">
              <i class="ti ti-mail"></i>
              <input
                type="email"
                id="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                placeholder="nama@email.com"
                autocomplete="username"
                autofocus
                required>
            </div>
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <label for="password" class="form-label mb-0">Password</label>
              <span class="auth-hint">Lupa password? Hubungi admin.</span>
            </div>
            <div class="auth-input auth-input-password mt-2">
              <i class="ti ti-lock"></i>
              <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Masukkan password"
                autocomplete="current-password"
                required>
              <button type="button" class="auth-password-toggle" id="toggle-password" aria-label="Tampilkan password">
                <i class="ti ti-eye"></i>
              </button>
            </div>
          </div>

          <div class="form-check mb-4">
            <input type="checkbox" id="remember" name="remember" value="1" class="form-check-input" @checked(old('remember'))>
            <label for="remember" class="form-check-label">Ingat saya di perangkat ini</label>
          </div>

          <button type="submit" class="btn btn-primary w-100 auth-submit">
            <span>Masuk</span>
            <i class="ti ti-arrow-right"></i>
          </button>
        </form>

        <!-- <div class="auth-divider"><span>atau</span></div>

        <a href="{{ route('welcome') }}" class="btn auth-back w-100">
          <i class="ti ti-arrow-left"></i>
          <span>Kembali ke halaman utama</span>
        </a> -->

        <div class="auth-copyright">
          &copy; {{ date('Y') }} {{ config('app.name') }}. Seluruh hak cipta dilindungi.
        </div>
      </div>
    </section>
  </main>

  <script>
    (function() {
      var toggle = document.getElementById('toggle-password');
      var password = document.getElementById('password');

      if (!toggle || !password) {
        return;
      }

      toggle.addEventListener('click', function() {
        var isHidden = password.type === 'password';
        password.type = isHidden ? 'text' : 'password';

        var icon = toggle.querySelector('i');
        if (icon) {
          icon.classList.toggle('ti-eye', !isHidden);
          icon.classList.toggle('ti-eye-off', isHidden);
        }

        toggle.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
      });
    })();
  </script>
</body>

</html>