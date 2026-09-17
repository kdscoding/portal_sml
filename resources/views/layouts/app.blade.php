<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal SML')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #f2f0ed;
            --bg-secondary: #faf8f5;
            --bg-tertiary: #ebe7e2;
            --bg-card: #faf8f5;
            --bg-input: #fdfcfb;
            --text-primary: #2d2d2d;
            --text-secondary: #454545;
            --text-muted: #5e5e5e;
            --border-color: #ddd8d2;
            --accent-green: #2d6a4f;
            --accent-green-dark: #1b4332;
            --accent-green-light: #52b788;
            --accent-green-gradient: linear-gradient(135deg, #1b4332, #2d6a4f, #52b788);
            --accent-blue: #1a3a6b;
            --accent-blue-light: #3a6ba8;
            --accent-orange: #a04000;
            --accent-red: #a83232;
            --accent-purple: #5a2d82;
            --success-bg: rgba(45, 106, 79, 0.08);
            --error-bg: rgba(168, 50, 50, 0.08);
            --info-bg: rgba(26, 58, 107, 0.08);
            --warning-bg: rgba(160, 64, 0, 0.08);
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.08);
            --shadow-card: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-card-hover: 0 10px 30px rgba(0, 0, 0, 0.1), 0 4px 8px rgba(0, 0, 0, 0.04);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-primary);
            background-image:
                radial-gradient(ellipse at 0% 0%, rgba(45, 106, 79, 0.04) 0%, transparent 50%),
                radial-gradient(ellipse at 100% 100%, rgba(26, 58, 107, 0.04) 0%, transparent 50%);
            background-attachment: fixed;
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .font-mono { font-family: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace; }

        /* ===== NAVBAR ===== */
        .navbar-dark-terminal {
            background: rgba(250, 248, 245, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        .navbar-dark-terminal .navbar-brand {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--accent-green) !important;
            letter-spacing: 0.5px;
        }

        .navbar-dark-terminal .navbar-brand i { color: var(--accent-green); }

        .navbar-dark-terminal .navbar-toggler {
            border-color: var(--border-color);
        }

        .navbar-dark-terminal .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23666' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-dark-terminal .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            padding: 0.6rem 1rem;
            transition: all 0.2s;
            border-radius: 0.375rem;
            font-size: 0.9rem;
        }

        .navbar-dark-terminal .nav-link:hover {
            color: var(--text-primary) !important;
            background-color: var(--bg-tertiary);
        }

        .navbar-dark-terminal .nav-link.active {
            color: var(--accent-green) !important;
            background-color: rgba(45, 106, 79, 0.08);
        }

        .navbar-dark-terminal .nav-link i { margin-right: 5px; }

        /* ===== LAYOUT ===== */
        .main-content {
            flex: 1;
            padding: 24px 0 40px;
        }

        /* ===== CARDS ===== */
        .card-dark {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            position: relative;
        }

        .card-dark::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--accent-green-gradient);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .card-dark:hover::before {
            opacity: 1;
        }

        .card-dark:hover {
            border-color: rgba(45, 106, 79, 0.2);
            box-shadow: var(--shadow-card-hover);
            transform: translateY(-2px);
        }

        .card-dark .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            font-weight: 600;
            padding: 1rem 1.25rem;
            border-radius: 0 !important;
            font-size: clamp(0.85rem, 1.5vw, 0.95rem);
        }

        .card-dark .card-header i { color: var(--accent-green); margin-right: 6px; }

        .card-dark .card-body { padding: 1.25rem; }

        /* ===== BUTTONS ===== */
        .btn-terminal {
            background-color: var(--bg-secondary);
            border: 1px solid var(--accent-green);
            color: var(--accent-green);
            font-weight: 600;
            border-radius: 0.5rem;
            padding: 0.55rem 1.25rem;
            font-size: 0.9rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-terminal::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(45, 106, 79, 0.1);
            border-radius: 50%;
            transition: width 0.4s, height 0.4s, top 0.4s, left 0.4s;
            transform: translate(-50%, -50%);
        }

        .btn-terminal:hover::before {
            width: 200%;
            height: 200%;
        }

        .btn-terminal span,
        .btn-terminal i {
            position: relative;
            z-index: 1;
        }

        .btn-terminal:hover {
            background-color: var(--accent-green);
            color: #fff;
            border-color: var(--accent-green);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-terminal-primary {
            background: var(--accent-green-gradient);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 0.5rem;
            padding: 0.55rem 1.25rem;
            font-size: 0.9rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-terminal-primary:hover {
            background: linear-gradient(135deg, #143a27, #1b4332, #3d8b68);
            border-color: transparent;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(45, 106, 79, 0.35);
        }

        .btn-terminal-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-terminal-outline-orange {
            background: transparent;
            border: 1px solid var(--accent-orange);
            color: var(--accent-orange);
            font-weight: 600;
            border-radius: 0.5rem;
            padding: 0.55rem 1.25rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .btn-terminal-outline-orange:hover {
            background: var(--accent-orange);
            color: #fff;
            border-color: var(--accent-orange);
        }

        /* ===== ALERTS ===== */
        .alert-terminal-success {
            background-color: var(--success-bg);
            color: var(--accent-green-dark);
            border: 1px solid rgba(45, 106, 79, 0.3);
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-terminal-error {
            background-color: var(--error-bg);
            color: var(--accent-red);
            border: 1px solid rgba(168, 50, 50, 0.3);
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-terminal-info {
            background-color: var(--info-bg);
            color: var(--accent-blue);
            border: 1px solid rgba(26, 58, 107, 0.3);
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ===== FORMS ===== */
        .form-dark .form-label {
            color: var(--text-secondary);
            font-weight: 700;
            font-size: 0.85rem;
        }

        .form-dark .form-control,
        .form-dark .form-select {
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 0.5rem;
            padding: 0.6rem 0.85rem;
            font-size: 0.95rem;
        }

        .form-dark .form-control:focus,
        .form-dark .form-select:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.12);
            background-color: var(--bg-input);
            color: var(--text-primary);
        }

        .form-dark .form-control::placeholder { color: var(--text-muted); }

        .form-dark .form-control.is-valid {
            border-color: var(--accent-green);
        }

        .form-dark .form-control.is-invalid {
            border-color: var(--accent-red);
        }

        .form-dark .form-text { color: var(--text-muted); font-size: 0.8rem; }

        .form-dark .input-group-text {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            border-radius: 0 0.5rem 0.5rem 0;
        }

        /* ===== BADGES ===== */
        .badge-terminal {
            background-color: var(--success-bg);
            color: var(--accent-green-dark);
            border: 1px solid rgba(45, 106, 79, 0.3);
            font-weight: 600;
        }

        .badge-terminal-secondary {
            background-color: var(--bg-tertiary);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            font-weight: 600;
        }

        /* ===== TABLES ===== */
        .table-dark-terminal {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-primary);
            margin-bottom: 0;
        }

        .table-dark-terminal > thead > tr > th {
            background-color: var(--bg-tertiary);
            border-bottom: 2px solid var(--border-color);
            color: var(--accent-green-dark);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.85rem 1rem;
            vertical-align: middle;
        }

        .table-dark-terminal > tbody > tr > td {
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            vertical-align: middle;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .table-dark-terminal > tbody > tr:hover {
            background-color: rgba(45, 106, 79, 0.04);
        }

        .table-dark-terminal > tbody > tr td {
            transition: background-color 0.15s;
        }

        /* ===== PROGRESS ===== */
        .progress-dark {
            height: 0.6rem;
            border-radius: 0.3rem;
            background-color: var(--bg-tertiary);
            overflow: hidden;
            position: relative;
        }

        .progress-bar-dark {
            background: var(--accent-green-gradient);
            border-radius: 0.3rem;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .progress-bar-dark::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            to { left: 100%; }
        }

        /* ===== STAT CARDS ===== */
        .stat-card-dark {
            background: rgba(250, 248, 245, 0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .stat-card-dark::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--accent-green-gradient);
            border-radius: 0 2px 2px 0;
        }

        .stat-card-dark::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60px;
            height: 60px;
            background: var(--accent-green-gradient);
            opacity: 0.06;
            border-radius: 0 1rem 0 60px;
            transition: opacity 0.3s;
        }

        .stat-card-dark:hover::after {
            opacity: 0.12;
        }

        .stat-card-dark:hover {
            border-color: rgba(45, 106, 79, 0.3);
            transform: translateY(-3px);
            box-shadow: var(--shadow-card-hover);
        }

        .stat-card-dark h6 {
            font-size: clamp(0.65rem, 1.2vw, 0.8rem);
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .stat-card-dark .stat-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(1.2rem, 2vw, 1.85rem);
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
            background: linear-gradient(135deg, var(--text-primary), var(--accent-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-card-dark .stat-icon {
            font-size: 1.5rem;
            opacity: 0.3;
        }

        /* ===== PAGE TITLES ===== */
        .page-title-dark {
            color: var(--text-primary);
            font-weight: 700;
            font-size: clamp(1.25rem, 2vw, 1.5rem);
            margin-bottom: 0.25rem;
            padding-left: 0.75rem;
            border-left: 3px solid var(--accent-green);
            background: linear-gradient(90deg, rgba(45, 106, 79, 0.06), transparent);
            border-radius: 0 4px 4px 0;
            padding: 0.35rem 0.75rem;
            background-size: 200% 100%;
            animation: gradient-shift 4s ease infinite;
        }

        .page-subtitle-dark {
            color: var(--text-muted);
            font-size: clamp(0.8rem, 1.5vw, 0.9rem);
            margin-bottom: 1.5rem;
        }

        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* ===== ACTIVITY ITEMS ===== */
        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 8px;
            margin: 0 -8px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s, margin 0.2s;
            border-radius: 6px;
        }

        .activity-item:hover {
            background-color: rgba(45, 106, 79, 0.03);
            margin: 0;
            padding: 12px;
        }

        .activity-item:last-child { border-bottom: none; }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.85rem;
        }

        .activity-icon.success {
            background: var(--success-bg);
            color: var(--accent-green-dark);
        }

        .activity-icon.error {
            background: var(--error-bg);
            color: var(--accent-red);
        }

        .activity-icon.info {
            background: var(--info-bg);
            color: var(--accent-blue);
        }

        /* ===== FOOTER ===== */
        footer.footer-dark {
            background: rgba(250, 248, 245, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            padding: 1.25rem 0;
            margin-top: auto;
            text-align: center;
            font-size: 0.8rem;
        }

        footer.footer-dark a {
            color: var(--accent-green-dark);
            text-decoration: none;
        }

        footer.footer-dark a:hover { color: var(--accent-green); }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #c4c0b8; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a49c; }

        .form-dark .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23888' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            background-size: 14px 10px;
            padding-right: 2.5rem;
        }

        /* ===== PRINT ===== */
        @media print {
            .navbar-dark-terminal, .main-content, footer { display: none; }
            body { visibility: hidden; background: #fff; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; background: #fff; }
            #print-area * { color: #000; }
        }

        /* ===== VERSION BADGE ===== */
        .version-badge-dark {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: var(--success-bg);
            color: var(--accent-green-dark);
            border: 1px solid rgba(45, 106, 79, 0.3);
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ===== DASHBOARD QUICK LINKS ===== */
        .quick-link-card {
            background: rgba(250, 248, 245, 0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            text-decoration: none;
            color: var(--text-primary);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .quick-link-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent-green-gradient);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .quick-link-card:hover::before {
            opacity: 1;
        }

        .quick-link-card:hover {
            border-color: rgba(45, 106, 79, 0.3);
            transform: translateY(-4px);
            box-shadow: var(--shadow-card-hover);
        }

        .quick-link-card i {
            font-size: 2.5rem;
            background: var(--accent-green-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
            display: block;
            transition: transform 0.3s;
        }

        .quick-link-card:hover i {
            transform: scale(1.1) translateY(-2px);
        }

        .quick-link-card h5 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--text-primary);
        }

        .quick-link-card p {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .spinner-border { animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* ===== COMPACT SINGLE-PAGE LAYOUT ===== */
        html, body { height: auto; min-height: 100vh; }
        .main-content { padding: 16px 0 24px; }
        .container { padding: 0 8px; }

        /* Compact stat cards */
        .stat-card-dark { padding: 0.85rem 1rem; }
        .stat-card-dark h6 { font-size: 0.65rem; margin-bottom: 0.3rem; }
        .stat-card-dark .stat-value { font-size: 1.2rem; }
        .stat-card-dark .stat-icon { font-size: 1.2rem; opacity: 0.25; }

        /* Compact cards */
        .card-dark .card-body { padding: 0.85rem 1rem; }
        .card-dark .card-header { padding: 0.7rem 1rem; font-size: 0.82rem; }

        /* Compact forms */
        .form-dark .form-control,
        .form-dark .form-select { padding: 0.5rem 0.7rem; font-size: 0.85rem; }
        .form-dark .form-label { font-size: 0.78rem; }
        .form-dark .form-text { font-size: 0.72rem; }

        /* Compact buttons */
        .btn-terminal, .btn-terminal-primary, .btn-terminal-outline-orange {
            padding: 0.45rem 1rem; font-size: 0.82rem;
        }

        /* Compact alerts */
        .alert-terminal-success, .alert-terminal-error, .alert-terminal-info {
            padding: 0.7rem 1rem; font-size: 0.85rem; margin-bottom: 0.75rem;
        }

        /* Compact tables */
        .table-dark-terminal > thead > tr > th { padding: 0.6rem 0.75rem; font-size: 0.75rem; }
        .table-dark-terminal > tbody > tr > td { padding: 0.55rem 0.75rem; font-size: 0.8rem; }

        /* Compact progress */
        .progress-dark { height: 0.5rem; }

        /* Compact badges */
        .badge-terminal, .badge-terminal-secondary { font-size: 0.75rem; padding: 2px 8px; }

        /* Page title compact */
        .page-title-dark { font-size: 1.1rem; margin-bottom: 0.15rem; padding-left: 0.5rem; }
        .page-subtitle-dark { font-size: 0.78rem; margin-bottom: 0.85rem; }

        /* Compact activity items */
        .activity-item { padding: 8px 4px; }
        .activity-icon { width: 28px; height: 28px; font-size: 0.7rem; }
        .activity-icon-sm { width: 22px; height: 22px; font-size: 0.6rem; }

        /* Single page fit: scan page */
        .single-page { min-height: calc(100vh - 60px - 20px); }

        /* Fix border color for version badge */
        .version-badge-dark { border-color: rgba(45, 106, 79, 0.3); }
        strong, b { color: var(--text-primary); }
        code { color: var(--accent-green-dark); background: var(--bg-tertiary); padding: 1px 4px; border-radius: 3px; font-size: 0.82em; }

        /* ===== UTILITY CLASSES ===== */
        .stat-card-green::before { background: var(--accent-green-gradient); }
        .stat-card-orange::before { background: linear-gradient(135deg, #a04000, #c85a00); }
        .text-gradient-green {
            background: var(--accent-green-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .text-gradient-orange {
            background: linear-gradient(135deg, #a04000, #c85a00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-muted);
        }
        .empty-state i {
            font-size: 2rem;
            opacity: 0.2;
            display: block;
            margin-bottom: 8px;
            color: var(--accent-green);
        }

        /* Focus visible for keyboard navigation */
        :focus-visible {
            outline: 2px solid var(--accent-green);
            outline-offset: 2px;
        }

        button:focus-visible,
        a:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 2px solid var(--accent-green);
            outline-offset: 2px;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.2);
        }

        /* Skip to content link for screen readers */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--accent-green);
            color: var(--bg-primary);
            padding: 8px 16px;
            z-index: 9999;
            font-weight: 700;
            text-decoration: none;
            border-radius: 0 0 4px 0;
            transition: top 0.2s;
        }

        .skip-link:focus {
            top: 0;
        }

        /* High contrast mode support */
        @media (prefers-contrast: more) {
            :root {
                --text-primary: #1a1a1a;
                --text-secondary: #333333;
                --text-muted: #555555;
                --border-color: #888888;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

<a href="#main-content" class="skip-link">Skip to main content</a>

<nav class="navbar navbar-expand-lg navbar-dark-terminal">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-terminal-fill"></i> Portal SML
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link @if(request()->is('/') || request()->is('dashboard')) active @endif"
                       href="/">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->is('inbound-check')) active @endif"
                       href="/inbound-check">
                        <i class="bi bi-search"></i> Scanning
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->is('import')) active @endif"
                       href="/import">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Import
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="main-content" id="main-content">
    <div class="container">
        @yield('content')
    </div>
</main>

<footer class="footer-dark">
    <div class="container">
        <span>&copy; 2026 Portal SML — Inbound Checking & Labeling</span>
        <span class="mx-3">|</span>
        <a href="/">Dashboard</a>
        <span class="mx-2">·</span>
        <a href="/inbound-check">Scanning</a>
        <span class="mx-2">·</span>
        <a href="/import">Import</a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
