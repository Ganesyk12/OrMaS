@extends('layouts.base')

@php
    $isIncoming = $direction === 'incoming';
    $title = $isIncoming ? 'Surat Masuk' : 'Surat Keluar';
    $pageTitle = 'Detail '.$title;
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        $title => route('letters.index', ['direction' => $direction]),
        $letter->letter_number => route('letters.show', ['direction' => $direction, 'letter' => $letter->id]),
    ];
@endphp

@section('title', 'Detail '.$title)

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ $letter->subject }}</h5>
          <a href="{{ route('letters.edit', ['direction' => $direction, 'letter' => $letter->id]) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr><th style="width:200px;">Jenis Surat</th><td><span class="status-badge {{ $isIncoming ? 'status-completed' : 'status-ongoing' }}">{{ $letter->direction_label }}</span></td></tr>
            <tr><th>Nomor Surat</th><td>{{ $letter->letter_number }}</td></tr>
            <tr><th>Perihal</th><td>{{ $letter->subject }}</td></tr>
            <tr><th>Kategori</th><td>{{ $letter->category->name ?? '-' }}</td></tr>
            <tr><th>Organisasi</th><td>{{ $letter->organization->name ?? '-' }}</td></tr>
            <tr><th>{{ $isIncoming ? 'Pengirim' : 'Tujuan' }}</th><td>{{ $isIncoming ? ($letter->sender ?? '-') : ($letter->recipient ?? '-') }}</td></tr>
            <tr><th>Tanggal Surat</th><td>{{ $letter->letter_date?->format('d M Y') ?? '-' }}</td></tr>
            @if($isIncoming)
              <tr><th>Tanggal Diterima</th><td>{{ $letter->received_date?->format('d M Y') ?? '-' }}</td></tr>
            @endif
            <tr><th>Catatan</th><td>{{ $letter->notes ?? '-' }}</td></tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card">
        <div class="card-header"><h6 class="mb-0">Informasi</h6></div>
        <div class="card-body">
          <div class="mb-3">
            <div class="text-muted small">Dibuat</div>
            <div class="fw-semibold">{{ $letter->created_at?->format('d M Y H:i') ?? '-' }}</div>
          </div>
          <div>
            <div class="text-muted small">Terakhir diperbarui</div>
            <div class="fw-semibold">{{ $letter->updated_at?->format('d M Y H:i') ?? '-' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('letters.index', ['direction' => $direction]) }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
