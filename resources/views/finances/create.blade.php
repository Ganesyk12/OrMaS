@extends('layouts.base')

@section('title', 'Tambah Transaksi')
@php
    $pageTitle = 'Tambah Transaksi';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Keuangan' => route('finances.index'),
        'Tambah' => route('finances.create'),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header"><h5 class="mb-0">Form Transaksi Keuangan</h5></div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('finances.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('finances._form', ['finance' => null])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
              <a href="{{ route('finances.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
