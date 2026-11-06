@extends('layouts.base')

@section('title', 'Akses Ditolak')
@php
    $pageTitle = 'Akses Ditolak';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Akses Ditolak' => url()->current(),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body text-center py-5">
          <div class="mb-3 text-danger">
            <i class="ti ti-lock-off" style="font-size: 4rem;"></i>
          </div>

          <h1 class="h3 mb-2">403 - Akses Ditolak</h1>
          <p class="text-muted mb-4">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk membuka halaman ini.' }}
          </p>

          <a href="{{ route('dashboard.index') }}" class="btn btn-primary">
            <i class="ti ti-dashboard me-1"></i> Kembali ke Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
