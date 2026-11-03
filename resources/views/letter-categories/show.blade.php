@extends('layouts.base')

@section('title', 'Detail Kategori Surat')
@php
    $pageTitle = 'Detail Kategori Surat';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Kategori Surat' => route('letter-categories.index'),
        $category->name => route('letter-categories.show', $category->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ $category->name }}</h5>
          <a href="{{ route('letter-categories.edit', $category->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr><th style="width:200px;">Kode</th><td>{{ $category->code }}</td></tr>
            <tr><th>Nama Kategori</th><td>{{ $category->name }}</td></tr>
            <tr><th>Deskripsi</th><td>{{ $category->description ?? '-' }}</td></tr>
            <tr><th>Jumlah Surat</th><td>{{ $category->letters_count }} surat</td></tr>
            <tr><th>Status</th><td>{{ $category->is_active ? 'Aktif' : 'Tidak Aktif' }}</td></tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card">
        <div class="card-header"><h6 class="mb-0">Surat Terbaru ({{ $category->letters_count }})</h6></div>
        <div class="card-body p-0">
          @forelse($category->letters as $letter)
            <div class="border-bottom px-3 py-2">
              <a href="{{ route('letters.show', ['direction' => $letter->direction, 'letter' => $letter->id]) }}" class="fw-semibold text-dark text-decoration-none">{{ $letter->subject }}</a>
              <div class="text-muted small">{{ $letter->letter_number }} · {{ $letter->direction_label }}</div>
            </div>
          @empty
            <div class="text-muted p-3">Belum ada surat pada kategori ini.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('letter-categories.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
