@extends('layouts.base')

@php
    $isIncoming = $direction === 'incoming';
    $title = $isIncoming ? 'Surat Masuk' : 'Surat Keluar';
    $pageTitle = 'Edit '.$title;
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        $title => route('letters.index', ['direction' => $direction]),
        'Edit' => route('letters.edit', ['direction' => $direction, 'letter' => $letter->id]),
    ];
@endphp

@section('title', 'Edit '.$title)

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Edit {{ $title }}: {{ $letter->letter_number }}</h5>
        </div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('letters.update', ['direction' => $direction, 'letter' => $letter->id]) }}" method="POST">
            @csrf
            @method('PUT')
            @include('letters._form', ['letter' => $letter, 'direction' => $direction, 'categories' => $categories])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Perubahan</button>
              <a href="{{ route('letters.index', ['direction' => $direction]) }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
