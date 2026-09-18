@extends('layouts.app')

@section('title', 'Data Visualization')

@section('content')
<div class="page-title-dark">📈 Visualisasi Data</div>
<div class="page-subtitle-dark">Analisis data numerik dari import Excel</div>

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

<div class="row g-1 mb-2">
    <div class="col-md-6">
        <div class="card card-dark">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart"></i> Records per Version</span>
                <select id="versionFilter" class="form-select form-select-sm form-dark" style="width:auto; min-width:180px;">
                    <option value="">Semua Versi</option>
                    @foreach($versions as $v)
                    <option value="{{ $v->upload_version }}">{{ $v->upload_version }}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-body">
                <canvas id="recordsChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-dark">
            <div class="card-header"><i class="bi bi-graph-up"></i> Total Qty per Version</div>
            <div class="card-body">
                <canvas id="qtyChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-1 mb-2">
    <div class="col-md-6">
        <div class="card card-dark">
            <div class="card-header"><i class="bi bi-pie-chart"></i> Status Distribution</div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-dark">
            <div class="card-header"><i class="bi bi-building"></i> Qty by Building</div>
            <div class="card-body">
                <canvas id="buildingChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-1 mb-2">
    <div class="col-md-6">
        <div class="card card-dark">
            <div class="card-header"><i class="bi bi-globe"></i> Qty by Country</div>
            <div class="card-body">
                <canvas id="countryChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-dark">
            <div class="card-header"><i class="bi bi-box-seam"></i> Qty by Item</div>
            <div class="card-body">
                <canvas id="itemChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card card-dark">
    <div class="card-header"><i class="bi bi-table"></i> Detail Statistics</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark-terminal" id="detailTable">
                <thead>
                    <tr>
                        <th>Version</th>
                        <th>Records</th>
                        <th>Total Qty</th>
                        <th>Avg Qty</th>
                        <th>Min Qty</th>
                        <th>Max Qty</th>
                        <th>Scanned</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($versions as $v)
                    <tr>
                        <td><span class="version-badge-dark">{{ $v->upload_version }}</span></td>
                        <td class="font-mono">{{ number_format($v->total) }}</td>
                        <td class="font-mono">{{ number_format($v->total_qty) }}</td>
                        <td class="font-mono">{{ number_format($v->avg_qty, 2) }}</td>
                        <td class="font-mono">{{ $v->min_qty }}</td>
                        <td class="font-mono">{{ $v->max_qty }}</td>
                        <td class="font-mono text-gradient-green">{{ $v->scanned }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress progress-dark" style="flex:1; min-width:60px;">
                                    <div class="progress-bar progress-bar-dark" style="width: {{ $v->percent }}%"></div>
                                </div>
                                <span class="font-mono" style="font-size:0.72rem; color: var(--text-muted);">{{ $v->percent }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const chartColors = {
        green: '#52b788',
        greenLight: 'rgba(82, 183, 136, 0.2)',
        blue: '#3a6ba8',
        blueLight: 'rgba(58, 107, 168, 0.2)',
        orange: '#d97706',
        orangeLight: 'rgba(217, 119, 6, 0.2)',
        purple: '#8b5cf6',
        purpleLight: 'rgba(139, 92, 246, 0.2)',
        red: '#ef4444',
        redLight: 'rgba(239, 68, 68, 0.2)',
    };

    const palette = [
        '#52b788', '#3a6ba8', '#d97706', '#8b5cf6', '#ef4444', 
        '#06b6d4', '#f43f5e', '#84cc16', '#ec4899', '#6366f1'
    ];

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: { color: '#999', font: { size: 11 } },
            },
            tooltip: {
                backgroundColor: '#1a1a1a',
                titleColor: '#fff',
                bodyColor: '#ddd',
                padding: 8,
                cornerRadius: 4,
            }
        },
        scales: {
            x: { ticks: { color: '#888', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: { ticks: { color: '#888', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.05)' }, beginAtZero: true },
        }
    };

    function createChart(ctx, type, data, options = {}) {
        return new Chart(ctx, { type, data, options: { ...chartOptions, ...options } });
    }

    let recordsChart, qtyChart, statusChart, buildingChart, countryChart, itemChart;

    function loadData(version = '') {
        fetch(`/visualization/data${version ? '?version=' + version : ''}`)
            .then(r => r.json())
            .then(res => {
                if (!res.success) return;
                const d = res.data;
                updateCharts(d);
            });
    }

    function updateCharts(d) {
        // Records per version
        if (recordsChart) recordsChart.destroy();
        const recData = {{ json_encode($charts['records_per_version']) }};
        recordsChart = createChart(document.getElementById('recordsChart'), 'bar', {
            labels: recData.map(r => r.version),
            datasets: [
                { label: 'Total Records', data: recData.map(r => r.total), backgroundColor: chartColors.greenLight, borderColor: chartColors.green, borderWidth: 1 },
                { label: 'Scanned', data: recData.map(r => r.scanned), backgroundColor: chartColors.blueLight, borderColor: chartColors.blue, borderWidth: 1 },
            ]
        });

        // Qty per version
        if (qtyChart) qtyChart.destroy();
        const qtyData = {{ json_encode($charts['qty_per_version']) }};
        qtyChart = createChart(document.getElementById('qtyChart'), 'line', {
            labels: qtyData.map(q => q.version),
            datasets: [
                { label: 'Total Qty', data: qtyData.map(q => q.total_qty), borderColor: chartColors.orange, backgroundColor: chartColors.orangeLight, fill: true, tension: 0.3 },
                { label: 'Avg Qty', data: qtyData.map(q => q.avg_qty), borderColor: chartColors.purple, backgroundColor: chartColors.purpleLight, fill: false, tension: 0.3, yAxisID: 'y1' },
            ]
        }, {
            scales: { ...chartOptions.scales, y1: { type: 'linear', position: 'right', grid: { drawOnChartArea: false }, ticks: { color: '#888', font: { size: 10 } } } }
        });

        // Status distribution
        if (statusChart) statusChart.destroy();
        const statusData = {{ json_encode($charts['status_distribution']) }};
        statusChart = createChart(document.getElementById('statusChart'), 'doughnut', {
            labels: ['Scanned', 'Pending'],
            datasets: [{ data: [statusData.scanned, statusData.pending], backgroundColor: [chartColors.greenLight, chartColors.orangeLight], borderColor: [chartColors.green, chartColors.orange], borderWidth: 2 }]
        });

        // Building chart - fetch from API
        fetchDataForChart('buildingChart', 'by_building', 'bar', 'Building');
        
        // Country chart
        fetchDataForChart('countryChart', 'by_country', 'bar', 'Country');
        
        // Item chart
        fetchDataForChart('itemChart', 'by_item', 'bar', 'Item');
    }

    function fetchDataForChart(canvasId, dataKey, type, label) {
        const version = document.getElementById('versionFilter').value;
        fetch(`/visualization/data${version ? '?version=' + version : ''}`)
            .then(r => r.json())
            .then(res => {
                if (!res.success) return;
                const groups = res.data[dataKey];
                const labels = Object.keys(groups).slice(0, 10);
                const counts = labels.map(k => groups[k].count);
                const sums = labels.map(k => groups[k].sum_qty);
                
                if (window[canvasId]) window[canvasId].destroy();
                window[canvasId] = createChart(document.getElementById(canvasId), type, {
                    labels: labels,
                    datasets: [
                        { label: label + ' Count', data: counts, backgroundColor: chartColors.greenLight, borderColor: chartColors.green, borderWidth: 1 },
                        { label: label + ' Sum Qty', data: sums, backgroundColor: chartColors.blueLight, borderColor: chartColors.blue, borderWidth: 1, type: 'line', yAxisID: 'y1', tension: 0.3, fill: false },
                    ]
                }, {
                    scales: { ...chartOptions.scales, y1: { type: 'linear', position: 'right', grid: { drawOnChartArea: false }, ticks: { color: '#888', font: { size: 10 } } } }
                });
            });
    }

    document.getElementById('versionFilter').addEventListener('change', function() {
        loadData(this.value);
    });

    // Initial load
    loadData();
</script>
@endsection