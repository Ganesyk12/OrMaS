@extends('layouts.base')

@section('title', 'Keuangan')
@php
$pageTitle = 'Keuangan';
$breadcrumbs = [
'Dashboard' => route('dashboard.index'),
'Keuangan' => route('finances.index'),
];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-xl-12">
      <div class="card h-100">
        <div class="card-header border-1 d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h4 mb-1 text-dark">Keuangan</h1>
            <p class="text-muted mb-0">Kelola transaksi keuangan</p>
          </div>
          <a href="{{ route('finances.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah Transaksi
          </a>
        </div>
        <div class="card-body p-0">
          <div class="card-header border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <span class="card-title-professional me-2">Daftar Transaksi</span>
            </div>
            <form method="GET" action="{{ route('finances.index') }}" class="d-flex">
              <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari kategori..." value="{{ request('search') }}">
              <select name="type" class="form-select form-select-sm ms-2" style="width:auto;">
                <option value="">Semua Tipe</option>
                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
              </select>
              <button type="submit" class="btn btn-outline-primary btn-sm ms-2"><i class="ti ti-search"></i></button>
            </form>
          </div>
          @if($finances->count())
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Tanggal</th>
                  <th>Organisasi</th>
                  <th>Tipe</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($finances as $fin)
                <tr>
                  <td class="fw-semibold text-dark">{{ $fin->transaction_date?->format('d M Y') ?? '-' }}</td>
                  <td class="text-muted small">{{ $fin->organization->name ?? '-' }}</td>
                  <td>
                    <span class="badge bg-{{ $fin->type === 'income' ? 'success' : 'danger' }}">{{ ucfirst($fin->type) }}</span>
                  </td>
                  <td class="text-muted small">
                    <div class="fw-semibold text-dark">Rp {{ number_format($fin->total, 0, ',', '.') }}</div>
                    <div class="text-muted">{{ $fin->details_count }} detail</div>
                  </td>
                  <td>
                    <span class="status-badge {{ $fin->status }}">{{ ucfirst($fin->status) }}</span>
                  </td>
                  <td class="text-end">
                    <div class="d-flex gap-1 justify-content-end">
                      <a href="{{ route('finances.show', $fin->id) }}" class="btn btn-outline-info btn-sm"><i class="ti ti-eye"></i></a>
                      <a href="{{ route('finances.edit', $fin->id) }}" class="btn btn-outline-primary btn-sm"><i class="ti ti-pencil"></i></a>
                      <form action="{{ route('finances.destroy', $fin->id) }}" method="POST" class="d-inline" data-confirm="Yakin hapus transaksi?">
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
          <div class="mt-3">{{ $finances->links() }}</div>
          @else
          <div class="text-center text-muted py-5">
            <i class="ti ti-information fs-4 mb-2 d-block opacity-50"></i>
            <div class="fw-semibold text-dark small">Tidak ada transaksi</div>
            <a href="{{ route('finances.create') }}" class="btn btn-primary mt-2">Tambah Transaksi</a>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection