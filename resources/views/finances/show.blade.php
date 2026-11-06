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
            <tr>
              <th style="width:200px;">Organisasi</th>
              <td>{{ $finance->organization->name ?? '-' }}</td>
            </tr>
            <tr>
              <th>Kegiatan</th>
              <td>{{ $finance->activity->title ?? '-' }}</td>
            </tr>
            <tr>
              <th>Tipe</th>
              <td>{{ $finance->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
            </tr>
            <tr>
              <th>Tanggal</th>
              <td>{{ $finance->transaction_date?->format('d M Y') ?? '-' }}</td>
            </tr>
            <tr>
              <th>Status</th>
              <td>{{ ucfirst($finance->status) }}</td>
            </tr>
            <tr>
              <th>Dibuat oleh</th>
              <td>{{ $finance->created_by ?? '-' }}</td>
            </tr>
            <tr>
              <th>Diperbarui oleh</th>
              <td>{{ $finance->updated_by ?? '-' }}</td>
            </tr>
          </table>

          <hr>

          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Rincian Transaksi</h6>
          </div>

          <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width: 50px;">No</th>
                  <th>Kategori</th>
                  <th>Bukti / Kwitansi</th>
                  <th>Dibuat oleh</th>
                  <th class="text-end">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                @forelse($finance->details as $detail)
                <tr>
                  <td class="text-muted small">{{ $loop->iteration }}</td>
                  <td class="fw-semibold text-dark">{{ $detail->category }}</td>
                  <td class="small">
                    @if($detail->receipt)
                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($detail->receipt) }}" target="_blank" rel="noopener">
                      {{ basename($detail->receipt) }}
                    </a>
                    @else
                    -
                    @endif
                  </td>
                  <td class="text-muted small">{{ $detail->created_by ?? '-' }}</td>
                  <td class="text-end text-muted small">Rp {{ number_format($detail->amount, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">Belum ada detail transaksi.</td>
                </tr>
                @endforelse
              </tbody>
              @if($finance->details->isNotEmpty())
              <tfoot class="table-light">
                <tr>
                  <th colspan="3" class="text-end">Total</th>
                  <th colspan="2" class="text-end">Rp {{ number_format($finance->total, 0, ',', '.') }}</th>
                </tr>
              </tfoot>
              @endif
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <a href="{{ route('finances.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection