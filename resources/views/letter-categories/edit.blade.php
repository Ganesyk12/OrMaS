@extends('layouts.base')

@section('title', 'Edit Kategori Surat')
@php
$pageTitle = 'Edit Kategori Surat';
$breadcrumbs = [
'Dashboard' => route('dashboard.index'),
'Kategori Surat' => route('letter-categories.index'),
'Edit' => route('letter-categories.edit', $category->id),
];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Edit Kategori: {{ $category->name }}</h5>
        </div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('letter-categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('letter-categories._form', ['category' => $category])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Perubahan</button>
              <a href="{{ route('letter-categories.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection