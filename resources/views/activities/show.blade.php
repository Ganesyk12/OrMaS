@extends('layouts.base')

@section('title', 'Detail Kegiatan')
@php
    $pageTitle = 'Detail Kegiatan';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Kegiatan' => route('activities.index'),
        $activity->title => route('activities.show', $activity->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ $activity->title }}</h5>
          <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr><th style="width:200px;">Organisasi</th><td>{{ $activity->organization->name ?? '-' }}</td></tr>
            <tr><th>Tipe</th><td>{{ ucfirst($activity->type) }}</td></tr>
            <tr><th>Status</th><td>{{ ucfirst($activity->status) }}</td></tr>
            <tr><th>Tanggal Mulai</th><td>{{ $activity->start_date?->format('d M Y') ?? '-' }}</td></tr>
            <tr><th>Tanggal Selesai</th><td>{{ $activity->end_date?->format('d M Y') ?? '-' }}</td></tr>
            <tr><th>Lokasi</th><td>{{ $activity->location ?? '-' }}</td></tr>
            <tr><th>Anggaran</th><td>Rp {{ number_format($activity->budget, 0, ',', '.') }}</td></tr>
            <tr><th>Deskripsi</th><td>{{ $activity->description ?? '-' }}</td></tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('activities.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
