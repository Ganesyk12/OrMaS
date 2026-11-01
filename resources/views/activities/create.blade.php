@extends('layouts.base')

@section('title', 'Tambah Kegiatan')
@php
    $pageTitle = 'Tambah Kegiatan';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Kegiatan' => route('activities.index'),
        'Tambah' => route('activities.create'),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header"><h5 class="mb-0">Form Kegiatan</h5></div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('activities.store') }}" method="POST">
            @csrf
            @include('activities._form', ['activity' => null])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
              <a href="{{ route('activities.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
