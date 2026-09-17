@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-title-dark">📊 Dashboard</div>
<div class="page-subtitle-dark">Ringkasan Inbound Checking & Labeling</div>

<div class="row g-1 mb-2">
    <div class="col-6 col-md-3">
        <div class="stat-card-dark">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Records</h6>
                    <div class="stat-value">{{ number_format($totalRecords) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-database"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-dark">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Packing Lists</h6>
                    <div class="stat-value">{{ $totalVersions }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-folder"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-dark stat-card-green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Di-scan</h6>
                    <div class="stat-value text-gradient-green">{{ number_format($totalScanned) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-dark stat-card-orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>Belum</h6>
                    <div class="stat-value text-gradient-orange">{{ number_format($totalPending) }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-clock"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-1 mb-2">
    <div class="col-lg-4 col-md-4 col-6">
        <a href="/inbound-check" class="quick-link-card d-block">
            <i class="bi bi-search"></i>
            <h5>Scanning</h5>
            <p>Scan & cetak stiker</p>
        </a>
    </div>
    <div class="col-lg-4 col-md-4 col-6">
        <a href="/import" class="quick-link-card d-block">
            <i class="bi bi-file-earmark-spreadsheet"></i>
            <h5>Import</h5>
            <p>Unggah Excel</p>
        </a>
    </div>
</div>

@if(!empty($versionProgress))
<div class="card card-dark mb-2">
    <div class="card-header"><i class="bi bi-graph-up"></i> Progress</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark-terminal">
                <thead>
                    <tr>
                        <th>Version</th>
                        <th>Scan</th>
                        <th>Total</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($versionProgress as $vp)
                    <tr>
                        <td><span class="version-badge-dark"><i class="bi bi-tag"></i> {{ $vp['version'] }}</span></td>
                        <td class="font-mono text-gradient-green">{{ $vp['scanned'] }}</td>
                        <td class="font-mono">{{ $vp['total'] }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress progress-dark" style="flex:1; min-width:60px;"><div class="progress-bar progress-bar-dark" style="width: {{ $vp['percent'] }}%"></div></div>
                                <span class="font-mono" style="font-size:0.72rem; color: var(--text-muted);">{{ $vp['percent'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="row g-1">
    <div class="col-lg-7">
        <div class="card card-dark">
            <div class="card-header"><i class="bi bi-clock-history"></i> Aktivitas Terakhir</div>
            <div class="card-body">
                @if($recentScans->isNotEmpty())
                    @foreach($recentScans->take(5) as $scan)
                    <div class="activity-item">
                        <div class="activity-icon success"><i class="bi bi-check"></i></div>
                        <div class="flex-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong style="font-size:0.88rem;">{{ $scan->id_sb_site }}</strong>
                                <span class="badge badge-terminal font-mono" style="font-size:0.68rem;">{{ $scan->upload_version }}</span>
                            </div>
                            <div class="text-muted" style="font-size:0.78rem;">
                                {{ $scan->item ?? '-' }} | PO: {{ $scan->po ?? '-' }} | Qty: {{ $scan->qty ?? '-' }}
                            </div>
                            <div style="font-size:0.7rem; color: var(--accent-blue-light);">
                                <i class="bi bi-clock"></i> {{ $scan->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        Belum ada aktivitas
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-dark">
            <div class="card-header"><i class="bi bi-info-circle"></i> Info Sistem</div>
            <div class="card-body">
                <div class="activity-item" style="border-bottom:none; padding: 4px 0;">
                    <div class="activity-icon info activity-icon-sm"><i class="bi bi-server"></i></div>
                    <div>
                        <div style="font-size:0.72rem; color: var(--accent-blue-light);">Database</div>
                        <div class="font-mono" style="font-size:0.8rem;">MySQL (MariaDB)</div>
                    </div>
                </div>
                <div class="activity-item" style="border-bottom:none; padding: 4px 0;">
                    <div class="activity-icon info activity-icon-sm"><i class="bi bi-table"></i></div>
                    <div>
                        <div style="font-size:0.72rem; color: var(--accent-blue-light);">Tabel Utama</div>
                        <div class="font-mono" style="font-size:0.8rem;">data_label_sbsite</div>
                    </div>
                </div>
                <div class="activity-item" style="border-bottom:none; padding: 4px 0;">
                    <div class="activity-icon info activity-icon-sm"><i class="bi bi-barcode"></i></div>
                    <div>
                        <div style="font-size:0.72rem; color: var(--accent-blue-light);">Kolom Barcode</div>
                        <div class="font-mono" style="font-size:0.8rem;">id_vendor</div>
                    </div>
                </div>
                <div class="activity-item" style="border-bottom:none; padding: 4px 0;">
                    <div class="activity-icon success activity-icon-sm"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <div style="font-size:0.72rem; color: var(--accent-blue-light);">Anti Duplikat</div>
                        <div class="font-mono text-gradient-green" style="font-size:0.8rem;">Aktif ✔</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
