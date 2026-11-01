@extends('layouts.base')

@section('title', 'Detail Hak Akses')
@php
    $pageTitle = 'Detail Hak Akses';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Hak Akses' => route('user-accesses.index'),
        'Detail' => route('user-accesses.show', $access->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Detail Hak Akses</h5>
          <a href="{{ route('user-accesses.edit', $access->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr><th style="width:200px;">Pengguna</th><td>{{ $access->user->name ?? '-' }}</td></tr>
            <tr><th>Email</th><td>{{ $access->user->email ?? '-' }}</td></tr>
            <tr><th>Modul</th><td>{{ $access->module }}</td></tr>
            <tr><th>Permission</th><td>{{ $access->permission }}</td></tr>
            <tr><th>Status</th><td>{{ $access->is_allowed ? 'Diizinkan' : 'Ditolak' }}</td></tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('user-accesses.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
