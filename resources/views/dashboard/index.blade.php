@extends('layouts.base')
@section('title', 'Dashboard')
@php
$pageTitle = 'Dashboard Pages';
$breadcrumbs = [
'Dashboard' => route('dashboard.index'),
];
@endphp

@push('styles')
<style>
    .stat-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        transition: box-shadow 0.2s ease;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-color: #d1d5db;
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1.25rem 1.25rem 0.75rem;
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .stat-sub {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 0.25rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #374151;
    }

    .stat-icon.primary {
        background: #dbeafe;
        color: #2563eb;
    }

    .stat-icon.info {
        background: #e0f2fe;
        color: #0369a1;
    }

    .stat-icon.warning {
        background: #fef3c7;
        color: #d97706;
    }

    .stat-icon.success {
        background: #dcfce7;
        color: #15803d;
    }

    .card-title-professional {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0;
    }

    .quick-action-item {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .quick-action-item:hover {
        border-color: #2563eb;
        background-color: #f8fafc;
        color: #111827;
        text-decoration: none;
    }

    .quick-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
        color: #374151;
        margin-right: 0.75rem;
    }

    .quick-action-item.primary .quick-action-icon {
        background: #eff6ff;
        color: #2563eb;
    }

    .quick-action-item.info .quick-action-icon {
        background: #f0f9ff;
        color: #0369a1;
    }

    .quick-action-item.warning .quick-action-icon {
        background: #fffbeb;
        color: #d97706;
    }

    .quick-action-item.success .quick-action-icon {
        background: #f0fdf4;
        color: #15803d;
    }

    .activity-item {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.875rem 1rem;
        margin-bottom: 0.75rem;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .activity-item:hover {
        border-color: #d1d5db;
        background-color: #f9fafb;
    }

    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #374151;
        flex-shrink: 0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-completed {
        background: #dcfce7;
        color: #166534;
    }

    .status-ongoing {
        background: #fef3c7;
        color: #92400e;
    }

    .status-pending {
        background: #f3f4f6;
        color: #374151;
    }

    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx2 = document.getElementById('financeChart');
        if (ctx2) {
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Pemasukan', 'Pengeluaran'],
                    datasets: [{
                        data: [<?php echo $stats['income'] ?? 0; ?>, <?php echo $stats['expense'] ?? 0; ?>],
                        backgroundColor: ['#10b981', '#ef4444'],
                        borderColor: '#ffffff',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#374151',
                                font: {
                                    size: 13
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 10,
                            cornerRadius: 6,
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.parsed || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header" style="background-color: #1B365D;">
            <!-- header kiri -->
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-white mb-0">Selamat datang kembali, {{ Auth::user()->name ?? 'Admin' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Statistic Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-header">
                                <div class="stat-content">
                                    <div class="stat-label">Total Anggota</div>
                                    <h2 class="stat-value mb-2">{{ $stats['members'] ?? 0 }}</h2>
                                    <span class="badge bg-light text-dark border">Terdaftar</span>
                                </div>
                                <div class="stat-icon info">
                                    <i class="ti ti-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-header">
                                <div class="stat-content">
                                    <div class="stat-label">Total Surat</div>
                                    <h2 class="stat-value mb-2">{{ $stats['letters'] ?? 0 }}</h2>
                                    <span class="badge bg-light text-dark border">Dibuat</span>
                                </div>
                                <div class="stat-icon info">
                                    <i class="ti ti-file-text"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-header">
                                <div class="stat-content">
                                    <div class="stat-label">Kegiatan</div>
                                    <h2 class="stat-value mb-2">{{ $stats['activities'] ?? 0 }}</h2>
                                    <span class="badge bg-light text-dark border">Terjadwal</span>
                                </div>
                                <div class="stat-icon warning">
                                    <i class="ti ti-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-header">
                                <div class="stat-content">
                                    <div class="stat-label">Saldo Keuangan</div>
                                    <h2 class="stat-value mb-2">Rp <?php echo number_format(($stats['income'] ?? 0) - ($stats['expense'] ?? 0), 0, ',', '.'); ?></h2>
                                    <span class="badge bg-light text-dark border">Terverifikasi</span>
                                </div>
                                <div class="stat-icon success">
                                    <i class="ti ti-currency-dollar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Charts -->
            <div class="row mb-4">
                <!-- Quick Actions -->
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-header border-0">
                            <div class="d-flex align-items-center">
                                <span class="card-title-professional me-2">Aksi Cepat</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <a href="{{ route('organizations.index') }}" class="quick-action-item d-flex align-items-center mb-2">
                                <div class="quick-action-icon primary"><i class="ti ti-building"></i></div>
                                <div>
                                    <div class="fw-semibold text-dark small">Kelola Organisasi</div>
                                    <div class="text-muted smallest">Tambah, edit, lihat organisasi</div>
                                </div>
                            </a>
                            <a href="{{ route('members.index') }}" class="quick-action-item d-flex align-items-center mb-2">
                                <div class="quick-action-icon info"><i class="ti ti-users"></i></div>
                                <div>
                                    <div class="fw-semibold text-dark small">Kelola Anggota</div>
                                    <div class="text-muted smallest">Data anggota Karang Taruna</div>
                                </div>
                            </a>
                            <a href="{{ route('activities.index') }}" class="quick-action-item d-flex align-items-center mb-2">
                                <div class="quick-action-icon warning"><i class="ti ti-calendar"></i></div>
                                <div>
                                    <div class="fw-semibold text-dark small">Kelola Kegiatan</div>
                                    <div class="text-muted smallest">Jadwal kegiatan rutin</div>
                                </div>
                            </a>
                            <a href="{{ route('finances.index') }}" class="quick-action-item d-flex align-items-center">
                                <div class="quick-action-icon success"><i class="ti ti-currency-dollar"></i></div>
                                <div>
                                    <div class="fw-semibold text-dark small">Transaksi Keuangan</div>
                                    <div class="text-muted smallest">Pemasukan dan pengeluaran</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Latest Finances -->
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-header border-0 d-flex justify-content-between align-items-center">
                            <span class="card-title-professional">Data Keuangan Terbaru</span>
                            <a href="{{ route('finances.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        </div>
                        <div class="card-body p-3">
                            @forelse($finances as $finance)
                            <a href="{{ route('finances.show', $finance->id) }}" class="activity-item d-flex align-items-start text-decoration-none">
                                <div class="flex-shrink-0">
                                    <div class="activity-icon">
                                        <i class="ti ti-{{ $finance->type === 'income' ? 'arrow-down-left' : 'arrow-up-right' }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold small {{ $finance->type === 'income' ? 'text-success' : 'text-danger' }}">
                                            {{ $finance->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                        </span>
                                        <small class="text-muted">{{ $finance->transaction_date?->format('d M Y') ?? '-' }}</small>
                                    </div>
                                    <div class="fw-semibold text-dark">Rp {{ number_format($finance->total, 0, ',', '.') }}</div>
                                    <div class="text-muted smaller">
                                        {{ $finance->organization->name ?? '-' }} · {{ $finance->details_count }} detail
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-information fs-4 mb-2 d-block opacity-50"></i>
                                <div class="fw-semibold text-dark small">Belum ada transaksi</div>
                                <div class="text-muted smallest">Tambahkan transaksi dari menu Keuangan.</div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Finance Chart -->
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-header border-0">
                            <div class="d-flex align-items-center">
                                <span class="card-title-professional me-2">Distribusi Keuangan</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="chart-container">
                                <canvas id="financeChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card h-100">
                        <div class="card-header border-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="card-title-professional me-2">Aktivitas Terbaru</span>
                            </div>
                            <a href="{{ route('activities.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($activities as $activity)
                                <div class="activity-item d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="activity-icon">
                                            <i class="ti ti-calendar"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="fw-semibold text-dark small">{{ $activity->title }}</div>
                                        <div class="text-muted smaller">{{ $activity->organization->name ?? '-' }}</div>
                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                            <span class="status-badge
                                                @if($activity->status === 'completed') status-completed
                                                @elseif($activity->status === 'ongoing') status-ongoing
                                                @else status-pending
                                            @endif">
                                                {{ ucfirst($activity->status) }}
                                            </span>
                                            <small class="text-muted">{{ $activity->start_date }}</small>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-muted py-5">
                                    <i class="ti ti-information fs-4 mb-2 d-block opacity-50"></i>
                                    <div class="fw-semibold text-dark small">Belum ada kegiatan</div>
                                    <div class="text-muted smallest">Mulai membuat kegiatan baru dari menu Kegiatan.</div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection