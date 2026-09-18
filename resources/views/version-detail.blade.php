@extends('layouts.app')

@section('title', 'Version Detail - {{ $version }}')

@section('content')
<div class="page-title-dark">📋 Detail Version</div>
<div class="page-subtitle-dark">{{ $version }}</div>

<div class="row g-1 mb-2">
    <div class="col-6 col-md-3">
        <div class="stat-card-dark">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Total Records</h6>
                    <div class="stat-value">{{ number_format($stats->total) }}</div>
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
                    <div class="stat-value text-gradient-green">{{ number_format($stats->total_qty) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-boxes"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-dark stat-card-blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Avg Qty</h6>
                    <div class="stat-value text-gradient-blue">{{ number_format($stats->avg_qty, 2) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-calculator"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-dark stat-card-orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Scanned</h6>
                    <div class="stat-value text-gradient-orange">{{ $stats->scanned }} / {{ $stats->total }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0"><i class="bi bi-table"></i> Data Records</h5>
    <form action="{{ route('import.version.destroy', $version) }}" method="POST" onsubmit="return confirm('Yakin hapus version ini? Semua data akan terhapus permanen!');">
        @method('DELETE')
        @csrf
        <button type="submit" class="btn btn-terminal-error btn-sm">
            <i class="bi bi-trash"></i> Hapus Version
        </button>
    </form>
</div>

<div class="card card-dark">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark-terminal mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>ID SB Site</th>
                        <th>ID Vendor</th>
                        <th>PO</th>
                        <th>Item</th>
                        <th>Country</th>
                        <th>Building</th>
                        <th>Cell</th>
                        <th>SDD</th>
                        <th class="font-mono" style="width:80px;">Qty</th>
                        <th style="width:80px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td class="font-mono text-muted">{{ $record->no_urut }}</td>
                        <td><span class="font-mono" style="font-size:0.78rem;">{{ $record->id_sb_site }}</span></td>
                        <td><span class="font-mono" style="font-size:0.78rem;">{{ $record->id_vendor }}</span></td>
                        <td><span class="font-mono" style="font-size:0.78rem;">{{ $record->po }}</span></td>
                        <td>{{ $record->item }}</td>
                        <td>{{ $record->country }}</td>
                        <td>{{ $record->building }}</td>
                        <td>{{ $record->cell }}</td>
                        <td>{{ $record->sdd ? \Carbon\Carbon::parse($record->sdd)->format('d/m/Y') : '-' }}</td>
                        <td class="font-mono">{{ number_format($record->qty) }}</td>
                        <td>
                            @if($record->status_received)
                                <span class="badge badge-terminal-success"><i class="bi bi-check"></i> Scanned</span>
                            @else
                                <span class="badge badge-terminal-warning"><i class="bi bi-clock"></i> Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($records->hasPages())
        <div class="card-footer bg-transparent border-top p-2">
            {{ $records->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<div class="mt-2">
    <a href="/visualization" class="btn btn-terminal btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Visualisasi</a>
    <a href="/import" class="btn btn-terminal btn-sm"><i class="bi bi-upload"></i> Import Baru</a>
</div>
@endsection