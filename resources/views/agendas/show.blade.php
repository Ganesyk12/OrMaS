@extends('layouts.base')

@section('title', 'Detail Agenda')
@php
    $pageTitle = 'Detail Agenda';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Rapat' => route('meetings.index'),
        $meeting->title => route('meetings.show', $meeting->id),
        'Agenda' => route('agendas.index', $meeting->id),
        $agenda->title => route('agendas.show', [$meeting->id, $agenda->id]),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ $agenda->title }}</h5>
          <a href="{{ route('agendas.edit', [$meeting->id, $agenda->id]) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr><th style="width:200px;">Rapat</th><td>{{ $meeting->title }}</td></tr>
            <tr><th>Urutan</th><td>{{ $agenda->order }}</td></tr>
            <tr><th>Penyaji</th><td>{{ $agenda->presenter ?? '-' }}</td></tr>
            <tr><th>Durasi</th><td>{{ $agenda->duration_minutes ? $agenda->duration_minutes.' menit' : '-' }}</td></tr>
            <tr><th>Status</th><td>{{ ucfirst($agenda->status) }}</td></tr>
            <tr><th>Deskripsi</th><td>{{ $agenda->description ?? '-' }}</td></tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('agendas.index', $meeting->id) }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
