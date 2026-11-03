@extends('layouts.base')

@section('title', 'Edit Transaksi')
@php
    $pageTitle = 'Edit Transaksi';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Keuangan' => route('finances.index'),
        'Edit' => route('finances.edit', $finance->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header"><h5 class="mb-0">Edit Transaksi</h5></div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('finances.update', $finance->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('finances._form', ['finance' => $finance])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Perubahan</button>
              <a href="{{ route('finances.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
