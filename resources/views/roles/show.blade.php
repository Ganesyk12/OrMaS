@extends('layouts.base')

@section('title', 'Detail Peran')
@php
    $pageTitle = 'Detail Peran';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Peran' => route('roles.index'),
        $role->name => route('roles.show', $role->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ $role->name }}</h5>
          <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr><th style="width:200px;">Kode</th><td>{{ $role->code }}</td></tr>
            <tr><th>Level</th><td>{{ $role->level }}</td></tr>
            <tr><th>Deskripsi</th><td>{{ $role->description ?? '-' }}</td></tr>
            <tr><th>Status</th><td>{{ $role->is_active ? 'Aktif' : 'Tidak Aktif' }}</td></tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card">
        <div class="card-header"><h6 class="mb-0">Anggota ({{ $role->members->count() }})</h6></div>
        <div class="card-body p-0">
          @forelse($role->members as $member)
            <div class="border-bottom px-3 py-2">
              <div class="fw-semibold">{{ $member->user->name ?? '-' }}</div>
              <div class="text-muted small">{{ $member->organization->name ?? '-' }}</div>
            </div>
          @empty
            <div class="text-muted p-3">Belum ada anggota.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('roles.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
