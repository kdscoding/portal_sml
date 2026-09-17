@extends('layouts.app')

@section('title', 'Import Data Label Excel')

@section('content')
<div class="page-title-dark">📤 Import Data Label</div>
<div class="page-subtitle-dark">Unggah Excel untuk data_label_sbsite</div>

<div class="alert alert-terminal-info mb-3">
    <i class="bi bi-info-circle"></i> Versi otomatis dibuat (format: YYYYMMDD-NN)
</div>

<div class="card card-dark">
    <div class="card-header"><i class="bi bi-file-earmark-spreadsheet"></i> Pilih File</div>
    <div class="card-body">
        <label for="file_excel" class="form-label">File Excel (.xlsx/.xls/.csv)</label>
        <input type="file" id="file_excel" class="form-control form-dark mb-2" accept=".xlsx,.xls,.csv">
        <div id="file_info" class="form-text" style="margin-top:-4px; margin-bottom:12px;"></div>

        <div class="d-flex gap-2">
            <button class="btn btn-terminal" onclick="doPreview()" id="btn_preview">
                <i class="bi bi-eye"></i> Preview
            </button>
            <button class="btn btn-terminal-primary" onclick="doImport()" id="btn_import" style="position:relative;">
                <span class="spinner-border spinner-border-sm" id="spinner_import" style="display:none;"></span>
                <i class="bi bi-download"></i> Import & Scan
            </button>
        </div>

        <div id="alert_box" class="mt-2" style="display:none;"></div>
    </div>
</div>

<div class="card card-dark mt-2" id="preview_row" style="display:none;">
    <div class="card-header"><i class="bi bi-table"></i> Preview (50 baris)</div>
    <div class="card-body">
        <div id="preview_info" class="text-muted mb-2" style="font-size:0.78rem;"></div>
        <div class="table-responsive">
            <table class="table table-dark-terminal" id="preview_table"></table>
        </div>
    </div>
</div>

<div class="card card-dark mt-2" id="result_row" style="display:none;">
    <div class="card-body" style="padding:0.7rem 1rem;">
        <div id="result_info" class="alert alert-terminal-success mb-0"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const fileInput = document.getElementById('file_excel');
    const fileInfo = document.getElementById('file_info');
    const alertBox = document.getElementById('alert_box');
    const previewRow = document.getElementById('preview_row');
    const previewTable = document.getElementById('preview_table');
    const previewInfo = document.getElementById('preview_info');
    const resultRow = document.getElementById('result_row');
    const resultInfo = document.getElementById('result_info');
    const btnImport = document.getElementById('btn_import');
    const spinnerImport = document.getElementById('spinner_import');

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileInfo.textContent = `File: ${this.files[0].name} (${(this.files[0].size / 1024 / 1024).toFixed(2)} MB)`;
        } else {
            fileInfo.textContent = '';
        }
    });

    function showAlert(message, type) {
        alertBox.style.display = 'block';
        alertBox.className = type === 'error' ? 'alert alert-terminal-error' : type === 'success' ? 'alert alert-terminal-success' : 'alert alert-terminal-info';
        alertBox.innerHTML = message;
        setTimeout(function() { alertBox.style.display = 'none'; }, 6000);
    }

    async function doPreview() {
        const file = fileInput.files[0];
        if (!file) { showAlert('<i class="bi bi-exclamation-circle"></i> Pilih file dulu!', 'error'); return; }
        const formData = new FormData();
        formData.append('file_excel', file);
        try {
            const res = await fetch('/import/preview', { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
            if (!res.ok) { throw new Error('Server error (' + res.status + ')'); }
            const data = await res.json();
            if (!data.success) { showAlert('<i class="bi bi-x-circle"></i> ' + data.message, 'error'); return; }
            previewRow.style.display = 'block';
            previewInfo.textContent = `Total: ${data.total_rows} (menampilkan 50 baris)`;
            let html = '<thead><tr>';
            data.headers.forEach(h => { html += `<th>${h}</th>`; });
            html += '</tr></thead><tbody>';
            data.rows.forEach(row => {
                html += '<tr>';
                data.headers.forEach(h => { html += `<td>${row[h] ?? ''}</td>`; });
                html += '</tr>';
            });
            html += '</tbody>';
            previewTable.innerHTML = html;
        } catch (err) {
            showAlert('<i class="bi bi-x-circle"></i> Preview gagal: ' + err.message, 'error');
        }
    }

    async function doImport() {
        const file = fileInput.files[0];
        if (!file) { showAlert('<i class="bi bi-exclamation-circle"></i> Pilih file dulu!', 'error'); return; }
        btnImport.disabled = true;
        spinnerImport.style.display = 'inline-block';
        resultRow.style.display = 'none';
        const formData = new FormData();
        formData.append('file_excel', file);
        try {
            const res = await fetch('/import', { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
            if (!res.ok) {
                const txt = await res.text();
                throw new Error('Server error (' + res.status + ')');
            }
            const data = await res.json();
            btnImport.disabled = false;
            spinnerImport.style.display = 'none';
            if (!data.success) { showAlert('<i class="bi bi-x-circle"></i> ' + data.message, 'error'); return; }
            showAlert('<i class="bi bi-check-circle"></i> <strong>' + data.message + '</strong>', 'success');
            resultRow.style.display = 'block';
            resultInfo.innerHTML = `<span class="version-badge-dark">${data.version || ''}</span> — ${data.total} baris di-import.`;
            previewRow.style.display = 'none';
            fileInput.value = '';
            fileInfo.textContent = '';
        } catch (err) {
            btnImport.disabled = false;
            spinnerImport.style.display = 'none';
            showAlert('<i class="bi bi-x-circle"></i> Gagal: ' + err.message, 'error');
        }
    }
</script>
@endsection
