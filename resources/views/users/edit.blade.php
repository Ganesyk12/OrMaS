@extends('layouts.base')

@section('title', 'Edit Pengguna')
@php
    $pageTitle = 'Edit Pengguna';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Pengguna' => route('users.index'),
        'Edit' => route('users.edit', $user->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header"><h5 class="mb-0">Edit Pengguna: {{ $user->name }}</h5></div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('users._form', ['user' => $user])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Perubahan</button>
              <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
