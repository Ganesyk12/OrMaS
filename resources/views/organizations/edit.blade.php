@extends('layouts.base')

@section('title', 'Edit Organisasi')
@php
$pageTitle = 'Edit Organisasi';
$breadcrumbs = [
'Dashboard' => route('dashboard.index'),
'Organisasi' => route('organizations.index'),
'Edit' => route('organizations.edit', $organization->id),
];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Edit Organisasi: {{ $organization->name }}</h5>
        </div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('organizations.update', $organization->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('organizations._form', ['organization' => $organization])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Perubahan</button>
              <a href="{{ route('organizations.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection