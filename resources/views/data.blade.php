@extends('layouts.app')

@section('title', 'Data Versions')

@section('content')
<div class="page-title-dark">📋 Data Versions</div>
<div class="page-subtitle-dark">Daftar versi import data label</div>

@if(session('success'))
<div class="alert alert-terminal-success alert-dismissible fade show mb-2" role="alert">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-terminal-error alert-dismissible fade show mb-2" role="alert">
    <i class="bi bi-x-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-1 mb-2">
    <div class="col-6 col-md-3">
        <div class="stat-card-dark">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Total Versi</h6>
                    <div class="stat-value">{{ $summary['total_versions'] }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-tag"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-dark">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Total Records</h6>
                    <div class="stat-value">{{ number_format($summary['total_records']) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-database"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-dark stat-card-green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Total Qty</h6>
                    <div class="stat-value text-gradient-green">{{ number_format($summary['total_qty']) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-boxes"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-dark stat-card-blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Sudah Di-scan</h6>
                    <div class="stat-value text-gradient-blue">{{ number_format($summary['total_scanned']) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="card card-dark">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-table"></i> Daftar Versi</span>
        <a href="/import" class="btn btn-terminal-primary btn-sm"><i class="bi bi-upload"></i> Import Baru</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark-terminal mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Version</th>
                        <th>Records</th>
                        <th>Total Qty</th>
                        <th>Avg Qty</th>
                        <th>Min Qty</th>
                        <th>Max Qty</th>
                        <th>Scanned</th>
                        <th>Progress</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($versions as $index => $v)
                    <tr>
                        <td class="font-mono text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="version-badge-dark">{{ $v->upload_version }}</span>
                        </td>
                        <td class="font-mono">{{ number_format($v->total) }}</td>
                        <td class="font-mono">{{ number_format($v->total_qty) }}</td>
                        <td class="font-mono">{{ number_format($v->avg_qty, 2) }}</td>
                        <td class="font-mono">{{ $v->min_qty }}</td>
                        <td class="font-mono">{{ $v->max_qty }}</td>
                        <td class="font-mono text-gradient-green">{{ $v->scanned }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress progress-dark" style="flex:1; min-width:80px;">
                                    <div class="progress-bar progress-bar-dark" style="width: {{ $v->percent }}%"></div>
                                </div>
                                <span class="font-mono" style="font-size:0.72rem; color: var(--text-muted);">{{ $v->percent }}%</span>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('data.version.show', $v->upload_version) }}" class="btn btn-terminal" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('import.version.destroy', $v->upload_version) }}" method="POST" onsubmit="return confirm('Yakin hapus version {{ $v->upload_version }}? Semua data akan terhapus permanen!');">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-terminal-error" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Belum ada data import</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection