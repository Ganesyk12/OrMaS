@extends('layouts.base')

@section('title', 'Tambah Agenda')
@php
    $pageTitle = 'Tambah Agenda';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Rapat' => route('meetings.index'),
        $meeting->title => route('meetings.show', $meeting->id),
        'Agenda' => route('agendas.index', $meeting->id),
        'Tambah' => route('agendas.create', $meeting->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header"><h5 class="mb-0">Form Agenda Rapat</h5></div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('agendas.store', $meeting->id) }}" method="POST">
            @csrf
            @include('agendas._form', ['agenda' => null])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
              <a href="{{ route('agendas.index', $meeting->id) }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
