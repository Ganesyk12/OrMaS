@extends('layouts.base')

@section('title', 'Edit Anggota')
@php
$pageTitle = 'Edit Anggota';
$breadcrumbs = [
'Dashboard' => route('dashboard.index'),
'Anggota' => route('members.index'),
'Edit' => route('members.edit', $member->id),
];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Edit Anggota: {{ $member->user->name ?? $member->member_number }}</h5>
        </div>
        <div class="card-body">
          @include('layouts.partials.form-errors')
          <form action="{{ route('members.update', $member->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('members._form', ['member' => $member])
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Perubahan</button>
              <a href="{{ route('members.index') }}" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection