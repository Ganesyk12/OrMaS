@extends('layouts.base')

@section('title', 'Kegiatan')
@php
    $pageTitle = 'Kegiatan';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Kegiatan' => route('activities.index'),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-xl-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h1 class="h4 mb-1 text-dark">Kegiatan</h1>
          <p class="text-muted mb-0">Kelola kegiatan rutin</p>
        </div>
        <a href="{{ route('activities.create') }}" class="btn btn-primary">
          <i class="ti ti-plus me-1"></i> Tambah Kegiatan
        </a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-12">
      <div class="card h-100">
        <div class="card-header border-0 d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <span class="card-title-professional me-2">Daftar Kegiatan</span>
          </div>
          <form method="GET" action="{{ route('activities.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari kegiatan..." value="{{ request('search') }}">
            <select name="status" class="form-select form-select-sm ms-2" style="width:auto;">
              <option value="">Semua Status</option>
              <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Perencanaan</option>
              <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
              <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
              <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <button type="submit" class="btn btn-outline-primary btn-sm ms-2"><i class="ti ti-search"></i></button>
          </form>
        </div>
        <div class="card-body p-0">
          @if($activities->count())
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Judul</th>
                    <th>Organisasi</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($activities as $act)
                    <tr>
                      <td class="fw-semibold text-dark">{{ $act->title }}</td>
                      <td class="text-muted small">{{ $act->organization->name ?? '-' }}</td>
                      <td>
                        <span class="status-badge {{ $act->status }}">
                          {{ ucfirst($act->status) }}
                        </span>
                      </td>
                      <td class="text-muted small">{{ $act->start_date?->format('d M Y') ?? '-' }}</td>
                      <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                          <a href="{{ route('activities.show', $act->id) }}" class="btn btn-outline-info btn-sm"><i class="ti ti-eye"></i></a>
                          <a href="{{ route('activities.edit', $act->id) }}" class="btn btn-outline-primary btn-sm"><i class="ti ti-pencil"></i></a>
                          <form action="{{ route('activities.destroy', $act->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kegiatan?')">
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
            <div class="mt-3">{{ $activities->links() }}</div>
          @else
            <div class="text-center text-muted py-5">
              <i class="ti ti-information fs-4 mb-2 d-block opacity-50"></i>
              <div class="fw-semibold text-dark small">Belum ada kegiatan</div>
              <a href="{{ route('activities.create') }}" class="btn btn-primary mt-2">Tambah Kegiatan</a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection