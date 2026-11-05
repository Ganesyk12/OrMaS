@extends('layouts.base')

@section('title', 'Pengguna')
@php
    $pageTitle = 'Pengguna';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Pengguna' => route('users.index'),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-xl-12">
      <div class="card h-100">
        <div class="card-header border-1 d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h4 mb-1 text-dark">Pengguna</h1>
            <p class="text-muted mb-0">Kelola akun pengguna sistem</p>
          </div>
          <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah Pengguna
          </a>
        </div>
        <div class="card-body p-0">
          <div class="card-header border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <span class="card-title-professional me-2">Daftar Pengguna</span>
            </div>
            <form method="GET" action="{{ route('users.index') }}" class="d-flex">
              <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari pengguna..." value="{{ request('search') }}">
              <button type="submit" class="btn btn-outline-primary btn-sm ms-2"><i class="ti ti-search"></i></button>
            </form>
          </div>
          @if($users->count())
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Terdaftar</th>
                    <th>Verifikasi</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $user)
                    <tr>
                      <td class="fw-semibold text-dark">{{ $user->name }}</td>
                      <td class="text-muted small">{{ $user->email }}</td>
                      <td class="text-muted small">{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                      <td>
                        <span class="status-badge {{ $user->email_verified_at ? 'status-completed' : 'status-pending' }}">
                          {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum' }}
                        </span>
                      </td>
                      <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                          <a href="{{ route('users.show', $user->id) }}" class="btn btn-outline-info btn-sm"><i class="ti ti-eye"></i></a>
                          <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm"><i class="ti ti-pencil"></i></a>
                          <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus pengguna ini?">
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
            <div class="mt-3">{{ $users->links() }}</div>
          @else
            <div class="text-center text-muted py-5">
              <i class="ti ti-information fs-4 mb-2 d-block opacity-50"></i>
              <div class="fw-semibold text-dark small">Belum ada pengguna</div>
              <a href="{{ route('users.create') }}" class="btn btn-primary mt-2">Tambah Pengguna</a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
