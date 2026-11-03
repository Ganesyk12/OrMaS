@extends('layouts.base')

@section('title', 'Edit Kegiatan')
@php
    $pageTitle = 'Edit Kegiatan';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Kegiatan' => route('activities.index'),
        'Edit' => route('activities.edit', $activity->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header"><h5 class="mb-0">Edit Kegiatan: {{ $activity->title }}</h5></div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('activities.update', $activity->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('activities._form', ['activity' => $activity])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Perubahan</button>
              <a href="{{ route('activities.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
