@extends('layouts.base')

@section('title', 'Tambah Kategori Surat')
@php
    $pageTitle = 'Tambah Kategori Surat';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Kategori Surat' => route('letter-categories.index'),
        'Tambah' => route('letter-categories.create'),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Form Kategori Surat</h5>
        </div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('letter-categories.store') }}" method="POST">
            @csrf
            @include('letter-categories._form', ['category' => null])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
              <a href="{{ route('letter-categories.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
