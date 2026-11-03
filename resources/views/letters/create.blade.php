@extends('layouts.base')

@php
    $isIncoming = $direction === 'incoming';
    $title = $isIncoming ? 'Surat Masuk' : 'Surat Keluar';
    $pageTitle = 'Tambah '.$title;
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        $title => route('letters.index', ['direction' => $direction]),
        'Tambah' => route('letters.create', ['direction' => $direction]),
    ];
@endphp

@section('title', 'Tambah '.$title)

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Form {{ $title }}</h5>
        </div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('letters.store', ['direction' => $direction]) }}" method="POST">
            @csrf
            @include('letters._form', ['letter' => null, 'direction' => $direction, 'categories' => $categories])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
              <a href="{{ route('letters.index', ['direction' => $direction]) }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
