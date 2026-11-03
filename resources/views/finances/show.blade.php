@extends('layouts.base')

@section('title', 'Detail Transaksi')
@php
    $pageTitle = 'Detail Transaksi';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Keuangan' => route('finances.index'),
        'Detail' => route('finances.show', $finance->id),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Detail Transaksi</h5>
          <a href="{{ route('finances.edit', $finance->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr><th style="width:200px;">Organisasi</th><td>{{ $finance->organization->name ?? '-' }}</td></tr>
            <tr><th>Kegiatan</th><td>{{ $finance->activity->title ?? '-' }}</td></tr>
            <tr><th>Tipe</th><td>{{ $finance->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td></tr>
            <tr><th>Kategori</th><td>{{ $finance->category }}</td></tr>
            <tr><th>Jumlah</th><td>Rp {{ number_format($finance->amount, 0, ',', '.') }}</td></tr>
            <tr><th>Tanggal</th><td>{{ $finance->transaction_date?->format('d M Y') ?? '-' }}</td></tr>
            <tr><th>Status</th><td>{{ ucfirst($finance->status) }}</td></tr>
            <tr><th>Bukti</th><td>{{ $finance->receipt ?? '-' }}</td></tr>
            <tr><th>Deskripsi</th><td>{{ $finance->description ?? '-' }}</td></tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('finances.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
