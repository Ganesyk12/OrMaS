@extends('layouts.base')

@section('title', 'Edit Peran')
@php
    $pageTitle = 'Edit Peran';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Peran' => route('roles.index'),
        'Edit' => route('roles.edit', $role->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header"><h5 class="mb-0">Edit Peran: {{ $role->name }}</h5></div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('roles._form', ['role' => $role])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Perubahan</button>
              <a href="{{ route('roles.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
