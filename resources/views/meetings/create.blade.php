@extends('layouts.base')

@section('title', 'Tambah Rapat')
@php
    $pageTitle = 'Tambah Rapat';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Rapat' => route('meetings.index'),
        'Tambah' => route('meetings.create'),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header"><h5 class="mb-0">Form Rapat</h5></div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('meetings.store') }}" method="POST">
            @csrf
            @include('meetings._form', ['meeting' => null])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
              <a href="{{ route('meetings.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
