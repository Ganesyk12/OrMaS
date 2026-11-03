@extends('layouts.base')

@section('title', 'Detail Anggota')
@php
$pageTitle = 'Detail Anggota';
$breadcrumbs = [
'Dashboard' => route('dashboard.index'),
'Anggota' => route('members.index'),
$member->member_number => route('members.show', $member->id),
];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Detail Anggota</h5>
          <a href="{{ route('members.edit', $member->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr>
              <th style="width:200px;">Nama</th>
              <td>{{ $member->user->name ?? '-' }}</td>
            </tr>
            <tr>
              <th>Email</th>
              <td>{{ $member->user->email ?? '-' }}</td>
            </tr>
            <tr>
              <th>Organisasi</th>
              <td>{{ $member->organization->name ?? '-' }}</td>
            </tr>
            <tr>
              <th>Peran / Jabatan</th>
              <td>{{ $member->role->name ?? '-' }}</td>
            </tr>
            <tr>
              <th>Nomor Anggota</th>
              <td>{{ $member->member_number }}</td>
            </tr>
            <tr>
              <th>Tanggal Bergabung</th>
              <td>{{ $member->join_date?->format('d M Y') ?? '-' }}</td>
            </tr>
            <tr>
              <th>Status</th>
              <td>{{ $member->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('members.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection