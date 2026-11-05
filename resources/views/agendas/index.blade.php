@extends('layouts.base')

@section('title', 'Agenda')
@php
    $pageTitle = 'Agenda';
    $breadcrumbs = [
        'Dashboard' => route('dashboard.index'),
        'Agenda' => route('agendas.index', request()->route('meeting')),
    ];
@endphp

@section('content')
<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-xl-12">
      <div class="card h-100">
        <div class="card-header border-1 d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h4 mb-1 text-dark">Agenda</h1>
            <p class="text-muted mb-0">Daftar agenda rapat</p>
          </div>
          <a href="{{ route('agendas.create', request()->route('meeting')) }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah Agenda
          </a>
        </div>
        <div class="card-body p-0">
          <div class="card-header border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <span class="card-title-professional me-2">Daftar Agenda</span>
            </div>
            <form method="GET" action="{{ route('agendas.index', request()->route('meeting')) }}" class="d-flex">
              <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari agenda..." value="{{ request('search') }}">
              <button type="submit" class="btn btn-outline-primary btn-sm ms-2"><i class="ti ti-search"></i></button>
            </form>
          </div>
          @if($agendas->count())
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($agendas as $agenda)
                    <tr>
                      <td class="fw-semibold text-dark">{{ $agenda->title }}</td>
                      <td class="text-muted small">{{ $agenda->description ?? '-' }}</td>
                      <td>
                        <span class="status-badge {{ $agenda->status }}">
                          {{ ucfirst($agenda->status) }}
                        </span>
                      </td>
                      <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                          <a href="{{ route('agendas.show', [$agenda->meeting_id, $agenda->id]) }}" class="btn btn-outline-info btn-sm"><i class="ti ti-eye"></i></a>
                          <a href="{{ route('agendas.edit', [$agenda->meeting_id, $agenda->id]) }}" class="btn btn-outline-primary btn-sm"><i class="ti ti-pencil"></i></a>
                          <form action="{{ route('agendas.destroy', [$agenda->meeting_id, $agenda->id]) }}" method="POST" class="d-inline" data-confirm="Yakin hapus agenda?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="ti ti-trash"></i></button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="mt-3">{{ $agendas->links() }}</div>
          @else
            <div class="text-center text-muted py-5">
              <i class="ti ti-information fs-4 mb-2 d-block opacity-50"></i>
              <div class="fw-semibold text-dark small">Tidak ada agenda</div>
              <a href="{{ route('agendas.create', request()->route('meeting')) }}" class="btn btn-primary mt-2">Tambah Agenda</a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
