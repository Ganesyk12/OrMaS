@extends('layouts.base')

@section('title', 'Detail Organisasi')
@php
    $pageTitle = 'Detail Organisasi';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Organisasi' => route('organizations.index'),
        $organization->name => route('organizations.show', $organization->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ $organization->name }}</h5>
          <a href="{{ route('organizations.edit', $organization->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr><th style="width:200px;">Kode</th><td>{{ $organization->code }}</td></tr>
            <tr><th>Tipe</th><td>{{ ucfirst($organization->type) }}</td></tr>
            <tr><th>Level</th><td>{{ $organization->level ?? '-' }}</td></tr>
            <tr><th>Alamat</th><td>{{ $organization->address ?? '-' }}</td></tr>
            <tr><th>Deskripsi</th><td>{{ $organization->description ?? '-' }}</td></tr>
            <tr><th>Tanggal Berdiri</th><td>{{ $organization->founded_at?->format('d M Y') ?? '-' }}</td></tr>
            <tr><th>Status</th><td>{{ $organization->is_active ? 'Aktif' : 'Tidak Aktif' }}</td></tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card">
        <div class="card-header"><h6 class="mb-0">Anggota ({{ $organization->members->count() }})</h6></div>
        <div class="card-body p-0">
          @forelse($organization->members as $member)
            <div class="border-bottom px-3 py-2">
              <div class="fw-semibold">{{ $member->user->name ?? '-' }}</div>
              <div class="text-muted small">{{ $member->role->name ?? '-' }} · {{ $member->member_number }}</div>
            </div>
          @empty
            <div class="text-muted p-3">Belum ada anggota.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('organizations.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
