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

        <div id="alert_box" class="mt-2" style="display:none;" role="alert" aria-live="polite"></div>
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
@endsection

@section('scripts')
<script>
    const fileInput = document.getElementById('file_excel');
    const fileInfo = document.getElementById('file_info');
    const alertBox = document.getElementById('alert_box');
    const previewRow = document.getElementById('preview_row');
    const previewTable = document.getElementById('preview_table');
    const previewInfo = document.getElementById('preview_info');
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

    function showImportAlert(type, title, message, details = []) {
        const alertClass = type === 'success'
            ? 'alert alert-terminal-success alert-dismissible fade show mt-2'
            : 'alert alert-terminal-error alert-dismissible fade show mt-2';
        const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-x-circle-fill';

        alertBox.className = alertClass;
        alertBox.style.display = 'block';
        alertBox.replaceChildren();

        const content = document.createElement('div');
        const heading = document.createElement('div');
        const iconElement = document.createElement('i');
        const titleElement = document.createElement('strong');
        const messageElement = document.createElement('div');

        iconElement.className = `bi ${icon} me-2`;
        iconElement.setAttribute('aria-hidden', 'true');
        titleElement.textContent = title;
        messageElement.textContent = message;

        heading.append(iconElement, titleElement);
        content.append(heading, messageElement);

        if (details.length > 0) {
            const detailsElement = document.createElement('ul');
            detailsElement.className = 'mb-0 mt-1';
            detailsElement.style.fontSize = '0.85rem';
            details.forEach((detail) => {
                const item = document.createElement('li');
                item.textContent = detail;
                detailsElement.append(item);
            });
            content.append(detailsElement);
        }

        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.className = 'btn-close';
        closeButton.setAttribute('data-bs-dismiss', 'alert');
        closeButton.setAttribute('aria-label', 'Tutup notifikasi');

        alertBox.append(content, closeButton);
    }

    async function doPreview() {
        const file = fileInput.files[0];
        if (!file) { showAlert('<i class="bi bi-exclamation-circle"></i> Pilih file dulu!', 'error'); return; }
        const formData = new FormData();
        formData.append('file_excel', file);
        try {
            const res = await fetch('/import/preview', { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Server error (' + res.status + ')');
            }
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
        if (!file) {
            showImportAlert('error', 'Import gagal', 'Pilih file yang akan di-import terlebih dahulu.');
            return;
        }

        btnImport.disabled = true;
        spinnerImport.style.display = 'inline-block';
        const formData = new FormData();
        formData.append('file_excel', file);

        try {
            const res = await fetch('/import', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            let data = null;
            try {
                data = await res.json();
            } catch (error) {
                data = null;
            }

            if (!res.ok || !data || !data.success) {
                const message = data && data.message
                    ? data.message
                    : 'Import gagal diproses. Silakan coba kembali.';
                showImportAlert('error', 'Import gagal', message);
                return;
            }

            showImportAlert('success', 'Import berhasil', 'Data berhasil di-import.', [
                `Version: ${data.version || '-'}`,
                `${data.total} baris berhasil disimpan`
            ]);
            previewRow.style.display = 'none';
            fileInput.value = '';
            fileInfo.textContent = '';
        } catch (error) {
            showImportAlert('error', 'Import gagal', 'Import tidak dapat diproses. Silakan coba kembali.');
        } finally {
            btnImport.disabled = false;
            spinnerImport.style.display = 'none';
        }
    }
</script>
@endsection
