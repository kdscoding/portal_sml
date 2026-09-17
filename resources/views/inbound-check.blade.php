@extends('layouts.app')

@section('title', 'Inbound Checking & Labeling')

@section('content')
<div class="single-page">
<div class="page-title-dark">📦 Inbound Checking & Labeling</div>
<div class="page-subtitle-dark">Proses scan barcode vendor, verifikasi duplikat, cetak stiker</div>

<div class="row g-1 mb-2">
    <div class="col-6 col-md-3">
        <div class="stat-card-dark">
            <h6>Total</h6>
            <div class="stat-value" id="stat_total">0</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-dark stat-card-green">
            <h6>Di-scan</h6>
            <div class="stat-value text-gradient-green" id="stat_scanned">0</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-dark stat-card-orange">
            <h6>Belum</h6>
            <div class="stat-value text-gradient-orange" id="stat_pending">0</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card-dark">
            <h6>Versions</h6>
            <div class="stat-value" id="stat_versions">0</div>
        </div>
    </div>
</div>

<div class="row g-1 mb-2">
    <div class="col-md-5">
        <div class="card card-dark">
            <div class="card-header"><i class="bi bi-link-45deg"></i> Packing List</div>
            <div class="card-body">
                <select id="upload_version" class="form-select form-dark">
                    <option value="">-- Pilih Packing List --</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row g-1 mb-2">
    <div class="col-12">
        <div class="card card-dark">
            <div class="card-header"><i class="bi bi-barcode"></i> Scan Barcode</div>
            <div class="card-body">
                <div class="input-group mb-1">
                    <input type="text" id="scan_input" class="form-control form-dark" placeholder="Scan barcode vendor..." autocomplete="off" autofocus>
                    <span class="input-group-text" id="scan_status"><i class="bi bi-circle-fill text-success"></i></span>
                </div>
                <div class="form-text mb-1">Tekan Enter untuk scan</div>
                <div id="alert_box" class="mb-1" style="display:none;"></div>
                <div id="last_scan" style="display:none;">
                    <div id="last_scan_alert" class="alert alert-terminal-info mb-0" style="display:none;">
                        <strong>📋:</strong> <span id="last_scan_info" class="font-mono"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-1">
    <div class="col-12">
        <div class="card card-dark">
            <div class="card-header"><i class="bi bi-graph-up"></i> Progress</div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong id="progress_version" class="text-muted font-mono" style="font-size:0.8rem;">-</strong>
                    <span>
                        <span id="progress_scanned" class="badge badge-terminal">0</span> /
                        <span id="progress_total" class="badge badge-terminal-secondary">0</span> Unit
                    </span>
                </div>
                <div class="progress progress-dark"><div class="progress-bar progress-bar-dark" id="progress_fill" role="progressbar" style="width: 0%"></div></div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
    const scanInput = document.getElementById('scan_input');
    const uploadVersionSelect = document.getElementById('upload_version');
    const alertBox = document.getElementById('alert_box');
    const lastScan = document.getElementById('last_scan');
    const lastScanAlert = document.getElementById('last_scan_alert');
    const lastScanInfo = document.getElementById('last_scan_info');
    const progressVersion = document.getElementById('progress_version');
    const progressScanned = document.getElementById('progress_scanned');
    const progressTotal = document.getElementById('progress_total');
    const progressFill = document.getElementById('progress_fill');
    const scanStatus = document.getElementById('scan_status');
    const statTotal = document.getElementById('stat_total');
    const statScanned = document.getElementById('stat_scanned');
    const statPending = document.getElementById('stat_pending');
    const statVersions = document.getElementById('stat_versions');

    function playAlarm(type) {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);

        if (type === 'tetot') {
            osc.type = 'square';
            osc.frequency.setValueAtTime(800, ctx.currentTime);
            osc.frequency.setValueAtTime(600, ctx.currentTime + 0.15);
            osc.frequency.setValueAtTime(800, ctx.currentTime + 0.3);
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.5);
        } else {
            osc.type = 'square';
            osc.frequency.setValueAtTime(400, ctx.currentTime);
            osc.frequency.setValueAtTime(300, ctx.currentTime + 0.2);
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.4);
        }
    }

    function showAlert(message, type) {
        alertBox.style.display = 'block';
        if (type === 'error') {
            alertBox.className = 'alert alert-terminal-error';
        } else if (type === 'success') {
            alertBox.className = 'alert alert-terminal-success';
        } else {
            alertBox.className = 'alert alert-terminal-info';
        }
        alertBox.innerHTML = message;
        setTimeout(function() {
            alertBox.style.display = 'none';
        }, 5000);
    }

    function updateStats(scanned, total, versions) {
        statTotal.textContent = total;
        statScanned.textContent = scanned;
        statPending.textContent = total - scanned;
        statVersions.textContent = versions;
        progressVersion.textContent = uploadVersionSelect.value || '-';
        progressScanned.textContent = scanned;
        progressTotal.textContent = total;
        var pct = total > 0 ? Math.round((scanned / total) * 100) : 0;
        progressFill.style.width = pct + '%';
    }

    function fetchVersions() {
        fetch('/inbound-check/versions')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                uploadVersionSelect.innerHTML = '<option value="">-- Pilih Packing List --</option>';
                data.forEach(function(v) {
                    var opt = document.createElement('option');
                    opt.value = v;
                    opt.textContent = v;
                    uploadVersionSelect.appendChild(opt);
                });
                fetchAllStats();
            });
    }

    function fetchAllStats() {
        fetch('/inbound-check/progress?upload_version=' + encodeURIComponent(uploadVersionSelect.value))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                updateStats(data.scanned, data.total, data.total);
            });
    }

    function fetchProgress() {
        var version = uploadVersionSelect.value;
        if (!version) {
            updateStats(0, 0, 0);
            return;
        }
        fetch('/inbound-check/progress?upload_version=' + encodeURIComponent(version))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var totalVersions = uploadVersionSelect.options.length - 1;
                updateStats(data.scanned, data.total, totalVersions);
            });
    }

    function printLabel(data) {
        document.getElementById('print_id_sb_site').textContent = data.id_sb_site;
        document.getElementById('print_item').textContent = data.item;
        document.getElementById('print_po').textContent = data.po;
        document.getElementById('print_country').textContent = data.country;
        document.getElementById('print_building').textContent = data.building;
        document.getElementById('print_cell').textContent = data.cell;
        document.getElementById('print_sdd').textContent = data.sdd;
        document.getElementById('print_qty').textContent = data.qty;
        window.print();
    }

    scanInput.addEventListener('keypress', function(e) {
        if (e.key !== 'Enter') return;

        var barcode = scanInput.value.trim();
        var version = uploadVersionSelect.value;

        if (!version) {
            showAlert('<i class="bi bi-exclamation-triangle"></i> Silakan pilih Packing List terlebih dahulu!', 'info');
            scanInput.value = '';
            scanInput.focus();
            return;
        }

        if (!barcode) return;

        scanStatus.innerHTML = '<i class="bi bi-hourglass-split text-warning"></i>';

        fetch('/inbound-check/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                barcode: barcode,
                upload_version: version
            })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            scanInput.classList.remove('is-invalid', 'is-valid');

            if (!data.found) {
                playAlarm('tetot');
                scanInput.value = '';
                scanInput.classList.add('is-invalid');
                scanInput.focus();
                scanStatus.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
                showAlert('<i class="bi bi-exclamation-circle"></i> <strong>Barang Tidak Terdaftar!</strong>', 'error');
                lastScan.style.display = 'none';
                lastScanAlert.style.display = 'none';
                return;
            }

            if (data.duplicate) {
                playAlarm('error');
                scanInput.value = '';
                scanInput.classList.add('is-invalid');
                scanInput.focus();
                scanStatus.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
                showAlert('<i class="bi bi-exclamation-triangle"></i> <strong>Barang Ini Sudah Pernah Di-scan Sebelumnya!</strong> (Duplikat)', 'error');
                return;
            }

            scanInput.classList.add('is-valid');
            scanStatus.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i>';
            showAlert('<i class="bi bi-check-circle"></i> <strong>' + data.message + '</strong>', 'success');

                lastScan.style.display = 'block';
                lastScanAlert.style.display = 'block';
            lastScanInfo.textContent =
                data.print_data.id_sb_site + ' | ' +
                data.print_data.item + ' | PO: ' + data.print_data.po + ' | ' +
                'Qty: ' + data.print_data.qty;

            printLabel(data.print_data);

            scanInput.value = '';
            scanInput.focus();
            fetchProgress();
        })
        .catch(function(err) {
            console.error(err);
            scanStatus.innerHTML = '<i class="bi bi-exclamation-circle text-danger"></i>';
            showAlert('<i class="bi bi-x-circle"></i> Gagal terhubung ke server!', 'error');
        });
    });

    uploadVersionSelect.addEventListener('change', function() {
        fetchProgress();
        lastScan.style.display = 'none';
        lastScanAlert.style.display = 'none';
        scanInput.value = '';
        scanInput.focus();
    });

fetchVersions();
    scanInput.focus();
</script>
@endsection

