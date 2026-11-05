@extends('layouts.base')

@section('title', 'Hak Akses')
@php
    $pageTitle = 'Hak Akses';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Hak Akses' => route('user-accesses.index'),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-xl-12">
      <div class="card h-100">
        <div class="card-header border-1 d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h4 mb-1 text-dark">Hak Akses</h1>
            <p class="text-muted mb-0">Kelola hak akses pengguna per modul & permission</p>
          </div>
          <a href="{{ route('user-accesses.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah Hak Akses
          </a>
        </div>
        <div class="card-body p-0">
          <div class="card-header border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <span class="card-title-professional me-2">Daftar Hak Akses</span>
            </div>
            <form method="GET" action="{{ route('user-accesses.index') }}" class="d-flex">
              <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari hak akses..." value="{{ request('search') }}">
              <select name="module" class="form-select form-select-sm ms-2" style="width:auto;">
                <option value="">Semua Modul</option>
                @foreach($modules as $module)
                  <option value="{{ $module->name }}" @selected(request('module') === $module->name)>{{ $module->label }}</option>
                @endforeach
              </select>
              <button type="submit" class="btn btn-outline-primary btn-sm ms-2"><i class="ti ti-search"></i></button>
            </form>
          </div>
          @if($accesses->count())
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Pengguna</th>
                    <th>Modul</th>
                    <th>Permission</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($accesses as $acc)
                    <tr>
                      <td class="fw-semibold text-dark">{{ $acc->user->name ?? '-' }}</td>
                      <td class="text-muted small">{{ $modules->firstWhere('name', $acc->module)?->label ?? $acc->module }}</td>
                      <td>
                        @php
                          $permissionLabels = ['view' => 'Lihat', 'create' => 'Tambah', 'edit' => 'Ubah', 'delete' => 'Hapus'];
                          $allowedPermissions = $acc->allowedPermissions();
                        @endphp
                        <div class="d-flex flex-wrap gap-1">
                          @forelse($allowedPermissions as $permission)
                            <span class="badge bg-light text-success border">{{ $permissionLabels[$permission] }}</span>
                          @empty
                            <span class="badge bg-light text-muted border">Tidak ada</span>
                          @endforelse
                        </div>
                      </td>
                      <td>
                        <span class="status-badge {{ $acc->hasAnyPermission() ? 'status-completed' : 'status-pending' }}">
                          {{ $acc->hasAnyPermission() ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                      </td>
                      <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                          <a href="{{ route('user-accesses.show', $acc->id) }}" class="btn btn-outline-info btn-sm"><i class="ti ti-eye"></i></a>
                          <a href="{{ route('user-accesses.edit', $acc->id) }}" class="btn btn-outline-primary btn-sm"><i class="ti ti-pencil"></i></a>
                          <form action="{{ route('user-accesses.destroy', $acc->id) }}" method="POST" class="d-inline" data-confirm="Yakin hapus hak akses?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="ti ti-trash"></i></button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="mt-3 px-3 pb-3">{{ $accesses->links() }}</div>
          @else
            <div class="text-center text-muted py-5">
              <i class="ti ti-information fs-4 mb-2 d-block opacity-50"></i>
              <div class="fw-semibold text-dark small">Tidak ada data hak akses</div>
              <a href="{{ route('user-accesses.create') }}" class="btn btn-primary mt-2">Tambah Hak Akses</a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
