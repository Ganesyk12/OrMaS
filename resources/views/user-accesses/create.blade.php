@extends('layouts.base')

@section('title', 'Tambah Hak Akses')
@php
$pageTitle = 'Tambah Hak Akses';
$breadcrumbs = [
'Dashboard' => route('dashboard.index'),
'Hak Akses' => route('user-accesses.index'),
'Tambah' => route('user-accesses.create'),
];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Form Hak Akses</h5>
        </div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('user-accesses.store') }}" method="POST">
            @csrf
            @include('user-accesses._form', ['access' => null])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
              <a href="{{ route('user-accesses.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection