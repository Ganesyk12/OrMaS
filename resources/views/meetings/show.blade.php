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
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ $meeting->title }}</h5>
          <a href="{{ route('meetings.edit', $meeting->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr>
              <th style="width:200px;">Organisasi</th>
              <td>{{ $meeting->organization->name ?? '-' }}</td>
            </tr>
            <tr>
              <th>Tanggal & Waktu</th>
              <td>{{ $meeting->meeting_date?->format('d M Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
              <th>Lokasi</th>
              <td>{{ $meeting->location ?? '-' }}</td>
            </tr>
            <tr>
              <th>Status</th>
              <td>{{ ucfirst($meeting->status) }}</td>
            </tr>
            <tr>
              <th>Deskripsi</th>
              <td>{{ $meeting->description ?? '-' }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('meetings.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection