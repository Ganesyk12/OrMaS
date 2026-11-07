@extends('layouts.base')

@php
    $isIncoming = $direction === 'incoming';
    $title = $isIncoming ? 'Surat Masuk' : 'Surat Keluar';
    $pageTitle = $title;
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        $title => route('letters.index', ['direction' => $direction]),
    ];
@endphp

@section('title', $title)

@section('content')
<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-xl-12">
      <div class="card h-100">
        <div class="card-header border-1 d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h4 mb-1 text-dark">{{ $title }}</h1>
            <p class="text-muted mb-0">{{ $isIncoming ? 'Daftar surat yang diterima organisasi' : 'Daftar surat yang dikirim oleh organisasi' }}</p>
          </div>
          <a href="{{ route('letters.create', ['direction' => $direction]) }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah {{ $title }}
          </a>
        </div>
        <div class="card-body p-0">
          <div class="card-header border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <span class="card-title-professional me-2">Daftar {{ $title }}</span>
            </div>
            <form class="d-flex" method="GET" action="{{ route('letters.index', ['direction' => $direction]) }}">
              <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nomor / perihal..." value="{{ request('search') }}">
              <select name="category_id" class="form-select form-select-sm ms-2" style="width:auto;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
              </select>
              <button type="submit" class="btn btn-outline-primary btn-sm ms-2"><i class="ti ti-search"></i></button>
            </form>
          </div>

          @if($letters->count())
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Nomor Surat</th>
                    <th>Tanggal Surat</th>
                    <th>Perihal</th>
                    <th>Kategori</th>
                    <th>Organisasi</th>
                    <th>{{ $isIncoming ? 'Pengirim' : 'Tujuan' }}</th>
                    @if($isIncoming)
                      <th>Diterima</th>
                    @endif
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($letters as $letter)
                    <tr>
                      <td><span class="badge bg-light text-dark border">{{ $letter->letter_number }}</span></td>
                      <td class="text-muted small">{{ $letter->letter_date?->format('d M Y') ?? '-' }}</td>
                      <td>
                        <div class="fw-semibold text-dark">{{ $letter->subject }}</div>
                        @if($letter->notes)
                          <div class="text-muted small text-truncate" style="max-width:280px;">{{ $letter->notes }}</div>
                        @endif
                      </td>
                      <td><span class="badge bg-light text-primary border">{{ $letter->category->name ?? '-' }}</span></td>
                      <td class="text-muted small">{{ $letter->organization->name ?? '-' }}</td>
                      <td class="text-muted small">{{ $isIncoming ? ($letter->sender ?? '-') : ($letter->recipient ?? '-') }}</td>
                      @if($isIncoming)
                        <td class="text-muted small">{{ $letter->received_date?->format('d M Y') ?? '-' }}</td>
                      @endif
                      <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                          <a href="{{ route('letters.show', ['direction' => $direction, 'letter' => $letter->id]) }}" class="btn btn-outline-info btn-sm"><i class="ti ti-eye"></i></a>
                          <a href="{{ route('letters.edit', ['direction' => $direction, 'letter' => $letter->id]) }}" class="btn btn-outline-primary btn-sm"><i class="ti ti-pencil"></i></a>
                          <form action="{{ route('letters.destroy', ['direction' => $direction, 'letter' => $letter->id]) }}" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus surat ini?">
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
            <div class="mt-3">{{ $letters->links() }}</div>
          @else
            <div class="text-center text-muted py-5">
              <i class="ti ti-mail fs-4 mb-2 d-block opacity-50"></i>
              <div class="fw-semibold text-dark small">Belum ada {{ strtolower($title) }}</div>
              <a href="{{ route('letters.create', ['direction' => $direction]) }}" class="btn btn-primary mt-2">Tambah {{ $title }}</a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
