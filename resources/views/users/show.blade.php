@extends('layouts.base')

@section('title', 'Detail Pengguna')
@php
    $pageTitle = 'Detail Pengguna';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Pengguna' => route('users.index'),
        $user->name => route('users.show', $user->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ $user->name }}</h5>
          <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr><th style="width:200px;">Nama</th><td>{{ $user->name }}</td></tr>
            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
            <tr><th>Terdaftar</th><td>{{ $user->created_at?->format('d M Y H:i') ?? '-' }}</td></tr>
            <tr><th>Email Terverifikasi</th><td>{{ $user->email_verified_at?->format('d M Y H:i') ?? 'Belum' }}</td></tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
