@extends('layouts.base')

@section('title', 'Detail Rapat')
@php
    $pageTitle = 'Detail Rapat';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Rapat' => route('meetings.index'),
        $meeting->title => route('meetings.show', $meeting->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ $meeting->title }}</h5>
          <a href="{{ route('meetings.edit', $meeting->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr><th style="width:200px;">Organisasi</th><td>{{ $meeting->organization->name ?? '-' }}</td></tr>
            <tr><th>Tanggal & Waktu</th><td>{{ $meeting->meeting_date?->format('d M Y H:i') ?? '-' }}</td></tr>
            <tr><th>Lokasi</th><td>{{ $meeting->location ?? '-' }}</td></tr>
            <tr><th>Status</th><td>{{ ucfirst($meeting->status) }}</td></tr>
            <tr><th>Deskripsi</th><td>{{ $meeting->description ?? '-' }}</td></tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0">Agenda ({{ $meeting->agendas->count() }})</h6>
          <a href="{{ route('agendas.create', $meeting->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-plus"></i></a>
        </div>
        <div class="card-body p-0">
          @forelse($meeting->agendas->sortBy('order') as $agenda)
            <div class="border-bottom px-3 py-2">
              <div class="fw-semibold">{{ $agenda->order }}. {{ $agenda->title }}</div>
              <div class="text-muted small">{{ ucfirst($agenda->status) }}{{ $agenda->presenter ? ' · '.$agenda->presenter : '' }}</div>
            </div>
          @empty
            <div class="text-muted p-3">Belum ada agenda.</div>
          @endforelse
        </div>
        <div class="card-footer">
          <a href="{{ route('agendas.index', $meeting->id) }}" class="btn btn-outline-primary btn-sm w-100">Kelola Agenda</a>
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('meetings.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
