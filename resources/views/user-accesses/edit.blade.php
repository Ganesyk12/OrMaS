@extends('layouts.base')

@section('title', 'Edit Hak Akses')
@php
    $pageTitle = 'Edit Hak Akses';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Hak Akses' => route('user-accesses.index'),
        'Edit' => route('user-accesses.edit', $access->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header"><h5 class="mb-0">Edit Hak Akses</h5></div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('user-accesses.update', $access->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('user-accesses._form', ['access' => $access])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Perubahan</button>
              <a href="{{ route('user-accesses.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
