<!-- [ Sidebar Menu ] start -->
@php
$currentUser = auth()->user();
$accessByModule = $currentUser
    ? \Modules\UserAccess\Models\UserAccessModel::where('user_id', $currentUser->id)->get()->keyBy('module')
    : collect();
$canView = fn (string $module): bool => (bool) ($accessByModule->get($module)?->allow_view ?? false);

$isOrganisasiActive = request()->routeIs('organizations.*') || request()->routeIs('members.*') || request()->routeIs('roles.*');
$isKegiatanActive = request()->routeIs('activities.*') || request()->routeIs('meetings.*');
$isSuratActive = request()->routeIs('letters.*') || request()->routeIs('letter-categories.*');
$currentLetterDirection = request()->route('direction');
$isKeuanganActive = request()->routeIs('finances.*');
$isPengaturanActive = request()->routeIs('users.*') || request()->routeIs('user-accesses.*');
@endphp
<nav class="pc-sidebar sidebar-dark" id="main-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="{{ route('dashboard.index') }}" class="b-brand text-primary">
        <img src="{{ asset('images/logo.webp') }}" alt="{{ config('app.name') }}" class="brand-logo">
        <span class="brand-name">{{ config('app.name') }}</span>
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item {{ request()->routeIs('dashboard.index') ? 'pc-active' : '' }}">
          <a href="{{ route('dashboard.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span><span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        @if($canView('organizations') || $canView('members') || $canView('roles'))
        <li class="pc-item pc-hasmenu {{ $isOrganisasiActive ? 'pc-trigger pc-active' : '' }}">
          <a href="#!" class="pc-link" aria-expanded="{{ $isOrganisasiActive ? 'true' : 'false' }}">
            <span class="pc-micon"><i class="ti ti-building"></i></span>
            <span class="pc-mtext">Organisasi</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @if($canView('organizations'))
            <li class="pc-item {{ request()->routeIs('organizations.*') ? 'pc-active' : '' }}">
              <a href="{{ route('organizations.index') }}" class="pc-link">
                <span class="pc-mtext">Data Organisasi</span>
              </a>
            </li>
            @endif
            @if($canView('members'))
            <li class="pc-item {{ request()->routeIs('members.*') ? 'pc-active' : '' }}">
              <a href="{{ route('members.index') }}" class="pc-link">
                <span class="pc-mtext">Anggota</span>
              </a>
            </li>
            @endif
            @if($canView('roles'))
            <li class="pc-item {{ request()->routeIs('roles.*') ? 'pc-active' : '' }}">
              <a href="{{ route('roles.index') }}" class="pc-link">
                <span class="pc-mtext">Role / Jabatan</span>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif

        @if($canView('activities') || $canView('meetings'))
        <li class="pc-item pc-hasmenu {{ $isKegiatanActive ? 'pc-trigger pc-active' : '' }}">
          <a href="#!" class="pc-link" aria-expanded="{{ $isKegiatanActive ? 'true' : 'false' }}">
            <span class="pc-micon"><i class="ti ti-calendar"></i></span>
            <span class="pc-mtext">Kegiatan</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @if($canView('activities'))
            <li class="pc-item {{ request()->routeIs('activities.*') ? 'pc-active' : '' }}">
              <a href="{{ route('activities.index') }}" class="pc-link">
                <span class="pc-mtext">Daftar Kegiatan</span>
              </a>
            </li>
            @endif
            @if($canView('meetings'))
            <li class="pc-item {{ request()->routeIs('meetings.*') ? 'pc-active' : '' }}">
              <a href="{{ route('meetings.index') }}" class="pc-link">
                <span class="pc-mtext">Rapat</span>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif

        @if($canView('letters') || $canView('letter-categories'))
        <li class="pc-item pc-hasmenu {{ $isSuratActive ? 'pc-trigger pc-active' : '' }}">
          <a href="#!" class="pc-link" aria-expanded="{{ $isSuratActive ? 'true' : 'false' }}">
            <span class="pc-micon"><i class="ti ti-mail"></i></span>
            <span class="pc-mtext">Surat</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @if($canView('letters'))
            <li class="pc-item {{ request()->routeIs('letters.*') && $currentLetterDirection === 'incoming' ? 'pc-active' : '' }}">
              <a href="{{ route('letters.index', ['direction' => 'incoming']) }}" class="pc-link">
                <span class="pc-mtext">Surat Masuk</span>
              </a>
            </li>
            <li class="pc-item {{ request()->routeIs('letters.*') && $currentLetterDirection === 'outgoing' ? 'pc-active' : '' }}">
              <a href="{{ route('letters.index', ['direction' => 'outgoing']) }}" class="pc-link">
                <span class="pc-mtext">Surat Keluar</span>
              </a>
            </li>
            @endif
            @if($canView('letter-categories'))
            <li class="pc-item {{ request()->routeIs('letter-categories.*') ? 'pc-active' : '' }}">
              <a href="{{ route('letter-categories.index') }}" class="pc-link">
                <span class="pc-mtext">Kategori Surat</span>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif

        @if($canView('finances'))
        <li class="pc-item pc-hasmenu {{ $isKeuanganActive ? 'pc-trigger pc-active' : '' }}">
          <a href="#!" class="pc-link" aria-expanded="{{ $isKeuanganActive ? 'true' : 'false' }}">
            <span class="pc-micon"><i class="ti ti-currency-dollar"></i></span>
            <span class="pc-mtext">Keuangan</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            <li class="pc-item {{ request()->routeIs('finances.*') ? 'pc-active' : '' }}">
              <a href="{{ route('finances.index') }}" class="pc-link">
                <span class="pc-mtext">Transaksi</span>
              </a>
            </li>
          </ul>
        </li>
        @endif

        @if($canView('users') || $canView('user-accesses'))
        <li class="pc-item pc-hasmenu {{ $isPengaturanActive ? 'pc-trigger pc-active' : '' }}">
          <a href="#!" class="pc-link" aria-expanded="{{ $isPengaturanActive ? 'true' : 'false' }}">
            <span class="pc-micon"><i class="ti ti-settings"></i></span>
            <span class="pc-mtext">Pengaturan</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @if($canView('users'))
            <li class="pc-item {{ request()->routeIs('users.*') ? 'pc-active' : '' }}">
              <a href="{{ route('users.index') }}" class="pc-link">
                <span class="pc-mtext">Pengguna</span>
              </a>
            </li>
            @endif
            @if($canView('user-accesses'))
            <li class="pc-item {{ request()->routeIs('user-accesses.*') ? 'pc-active' : '' }}">
              <a href="{{ route('user-accesses.index') }}" class="pc-link">
                <span class="pc-mtext">Hak Akses</span>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
      </ul>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->
