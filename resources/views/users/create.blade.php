@extends('layouts.base')

@section('title', 'Tambah Pengguna')
@php
    $pageTitle = 'Tambah Pengguna';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Pengguna' => route('users.index'),
        'Tambah' => route('users.create'),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header"><h5 class="mb-0">Form Pengguna</h5></div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('users.store') }}" method="POST">
            @csrf
            @include('users._form', ['user' => null])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
              <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
