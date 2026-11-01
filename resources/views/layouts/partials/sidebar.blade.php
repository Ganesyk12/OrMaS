<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar sidebar-dark" id="main-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="{{ route('dashboard.index') }}" class="b-brand text-primary">
        <h3 class="text-white"><?= config('app.name') ?></h3>
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item pc-caption"><label>Dashboard</label><i class="ti ti-dashboard"></i></li>
        <li class="pc-item {{ request()->routeIs('dashboard.index') ? 'pc-active' : '' }}">
          <a href="{{ route('dashboard.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span><span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <li class="pc-item pc-caption"><label>Organisasi</label><i class="ti ti-building"></i></li>
        <li class="pc-item {{ request()->routeIs('organizations.*') ? 'pc-active' : '' }}">
          <a href="{{ route('organizations.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-building"></i></span><span class="pc-mtext">Organisasi</span>
          </a>
        </li>
        <li class="pc-item {{ request()->routeIs('members.*') ? 'pc-active' : '' }}">
          <a href="{{ route('members.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span><span class="pc-mtext">Anggota</span>
          </a>
        </li>
        <li class="pc-item {{ request()->routeIs('roles.*') ? 'pc-active' : '' }}">
          <a href="{{ route('roles.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-badge"></i></span><span class="pc-mtext">Role / Jabatan</span>
          </a>
        </li>

        <li class="pc-item pc-caption"><label>Kegiatan</label><i class="ti ti-calendar"></i></li>
        <li class="pc-item {{ request()->routeIs('activities.*') ? 'pc-active' : '' }}">
          <a href="{{ route('activities.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-calendar"></i></span><span class="pc-mtext">Kegiatan</span>
          </a>
        </li>
        <li class="pc-item {{ request()->routeIs('meetings.*') ? 'pc-active' : '' }}">
          <a href="{{ route('meetings.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-video"></i></span><span class="pc-mtext">Rapat</span>
          </a>
        </li>

        <li class="pc-item pc-caption"><label>Keuangan</label><i class="ti ti-currency-dollar"></i></li>
        <li class="pc-item {{ request()->routeIs('finances.*') ? 'pc-active' : '' }}">
          <a href="{{ route('finances.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-currency-dollar"></i></span><span class="pc-mtext">Transaksi</span>
          </a>
        </li>

        <li class="pc-item pc-caption"><label>Pengaturan</label><i class="ti ti-settings"></i></li>
        <li class="pc-item {{ request()->routeIs('users.*') ? 'pc-active' : '' }}">
          <a href="{{ route('users.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-user"></i></span><span class="pc-mtext">Pengguna</span>
          </a>
        </li>
        <li class="pc-item {{ request()->routeIs('user-accesses.*') ? 'pc-active' : '' }}">
          <a href="{{ route('user-accesses.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-shield"></i></span><span class="pc-mtext">Hak Akses</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->