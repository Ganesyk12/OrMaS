@extends('layouts.base')

@section('title', 'Kategori Surat')
@php
    $pageTitle = 'Kategori Surat';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Kategori Surat' => route('letter-categories.index'),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-xl-12">
      <div class="card h-100">
        <div class="card-header border-1 d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h4 mb-1 text-dark">Kategori Surat</h1>
            <p class="text-muted mb-0">Master data kategori yang digunakan pada form surat masuk & keluar</p>
          </div>
          <a href="{{ route('letter-categories.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah Kategori
          </a>
        </div>
        <div class="card-body p-0">
          <div class="card-header border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <span class="card-title-professional me-2">Daftar Kategori Surat</span>
            </div>
            <form class="d-flex" method="GET" action="{{ route('letter-categories.index') }}">
              <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari kode / nama..." value="{{ request('search') }}">
              <select name="is_active" class="form-select form-select-sm ms-2" style="width:auto;">
                <option value="">Semua Status</option>
                <option value="1" @selected(request('is_active') === '1')>Aktif</option>
                <option value="0" @selected(request('is_active') === '0')>Tidak Aktif</option>
              </select>
              <button type="submit" class="btn btn-outline-primary btn-sm ms-2"><i class="ti ti-search"></i></button>
            </form>
          </div>

          @if($categories->count())
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Kode</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Surat</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($categories as $category)
                    <tr>
                      <td><span class="badge bg-light text-dark border">{{ $category->code }}</span></td>
                      <td class="fw-semibold text-dark">{{ $category->name }}</td>
                      <td class="text-muted small">{{ $category->description ?? '-' }}</td>
                      <td class="text-muted small">{{ $category->letters_count }} surat</td>
                      <td>
                        <span class="status-badge {{ $category->is_active ? 'status-completed' : 'status-pending' }}">
                          {{ $category->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                      </td>
                      <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                          <a href="{{ route('letter-categories.show', $category->id) }}" class="btn btn-outline-info btn-sm"><i class="ti ti-eye"></i></a>
                          <a href="{{ route('letter-categories.edit', $category->id) }}" class="btn btn-outline-primary btn-sm"><i class="ti ti-pencil"></i></a>
                          <form action="{{ route('letter-categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori surat ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" @disabled($category->letters_count > 0) title="{{ $category->letters_count > 0 ? 'Kategori masih digunakan oleh surat' : 'Hapus' }}"><i class="ti ti-trash"></i></button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="mt-3">{{ $categories->links() }}</div>
          @else
            <div class="text-center text-muted py-5">
              <i class="ti ti-information fs-4 mb-2 d-block opacity-50"></i>
              <div class="fw-semibold text-dark small">Belum ada kategori surat</div>
              <a href="{{ route('letter-categories.create') }}" class="btn btn-primary mt-2">Tambah Kategori</a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
