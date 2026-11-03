@extends('layouts.base')

@section('title', 'Member')
@php
$pageTitle = 'Member';
$breadcrumbs = [
'Dashboard' => route('dashboard.index'),
'Member' => route('members.index'),
];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-xl-12">
      <div class="card h-100">
        <div class="card-header border-1 d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h4 mb-1 text-dark">Member</h1>
            <p class="text-muted mb-0">Kelola data anggota Karang Taruna</p>
          </div>
          <a href="{{ route('members.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah Anggota
          </a>
        </div>
        <div class="card-header border-0 d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <span class="card-title-professional me-2">Daftar Anggota</span>
          </div>
          <div class="d-flex gap-2">
            <form method="GET" action="{{ route('members.index') }}" class="d-flex">
              <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari anggota..." value="{{ request('search') }}">
              <button type="submit" class="btn btn-outline-primary btn-sm ms-2"><i class="ti ti-search"></i></button>
            </form>
          </div>
        </div>
        <div class="card-body p-0">
          @if($members->count())
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Organisasi</th>
                  <th>Peran</th>
                  <th>Status</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($members as $member)
                <tr>
                  <td class="fw-semibold text-dark">{{ $member->user->name ?? '-' }}</td>
                  <td class="text-muted small">{{ $member->user->email ?? '-' }}</td>
                  <td class="text-muted small">{{ $member->organization->name ?? '-' }}</td>
                  <td class="text-muted small">{{ $member->role->name ?? '-' }}</td>
                  <td>
                    <span class="status-badge {{ $member->is_active ? 'status-completed' : 'status-pending' }}">
                      {{ $member->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                  </td>
                  <td class="text-end">
                    <div class="d-flex gap-1 justify-content-end">
                      <a href="{{ route('members.show', $member->id) }}" class="btn btn-outline-info btn-sm"><i class="ti ti-eye"></i></a>
                      <a href="{{ route('members.edit', $member->id) }}" class="btn btn-outline-primary btn-sm"><i class="ti ti-pencil"></i></a>
                      <form action="{{ route('members.destroy', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
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
          <div class="mt-3">{{ $members->links() }}</div>
          @else
          <div class="text-center text-muted py-5">
            <i class="ti ti-information fs-4 mb-2 d-block opacity-50"></i>
            <div class="fw-semibold text-dark small">Belum ada anggota</div>
            <div class="text-muted smallest">Mulai menambahkan anggota pertama.</div>
            <a href="{{ route('members.create') }}" class="btn btn-primary mt-2">Tambah Anggota</a>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection