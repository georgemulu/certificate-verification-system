<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Certificate Verification System') ?></title>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        :root {
            --sidebar-bg:       #1e3a5f;
            --sidebar-hover:    #274e7e;
            --sidebar-active:   #2d6a9f;
            --sidebar-text:     #c8daf0;
            --sidebar-width:    240px;
            --navbar-bg:        #ffffff;
            --navbar-border:    #e8edf3;
            --body-bg:          #f4f7fb;
            --card-bg:          #ffffff;
            --card-shadow:        0 2px 12px rgba(30,58,95,0.08);
            --text-primary:     #1a2b42;
            --text-secondary:   #5a6e85;
            --accent:           #2d6a9f;
            --accent-light:     #e8f1fa;
            --success:          #1a7f5a;
            --success-light:    #e6f7f1;
            --danger:           #c0392b;
            --danger-light:     #fdecea;
            --warning:          #b7791f;
            --warning-light:    #fef3dc;
            --border:           #e2e9f0;
            --radius:           10px;
            --font-body:        Helvetica, Arial, sans-serif;
        }

        *,*::before, ::after{ box-sizing: border-box; margin: 0; padding: 0;}

        body {
            font-family: var(--font-body);
            background: var(--body-bg);
            color: var(--text-primary);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255, 0.08);
        }

        .sidebar-brand h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.3;
        }

        .sidebar-brand span {
            font-size: 0.7rem;
            color: var(--sidebar-text);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }

        .nav-section-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(200,218,240,0.45);
            padding: 0.75rem 1.25rem 0.35rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1.25rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.15s, color 0.15s;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
        }

        .nav-link.active{
            background: var(--sidebar-active);
            color: #ffffff;
            border-left-color: #63b3ed;
        }

        .nav-link svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255, 0.08);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.65rem 1 rem;
            background: rgba(192,57,43,0.15);
            color: #f5a89a;
            border: 1px solid rgba(192,57,43,0.25);
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s;
            font-family: var(--font-body);
        }

        .logout-btn:hover {
            background: rgba(192,57,43,0.3);
            color: #ffffff;
        }

        .logout-btn svg {
            width: 16px;
            height: 16px;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
            overflow-x: hidden;
        }

        .navbar{
            background: var(--navbar-bg);
            border-bottom: 1px solid var(--navbar-border);
            padding: 0 2rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            width: 100%;
            box-sizing: border-box;
        }

        .navbar-left h1 {
            font-size: 1.1rem;
            color: var(--text-primary);
            font-weight: 700;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.4rem 0.85rem;
            background: var(--accent-light);
            border-radius: 999px;
        }

        .user-badge .avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--accent);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .user-badge .name {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--accent);
        }

        .role-pill {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .role-pill.admin { background: #fef3dc; color: #b7791f; }
        .role-pill.user {background: var(--accent-light); color: var(--accent);}
        .role-pill.verifier{background: var(--success-light); color: var(--success);}

        .page-content {
            padding: 2rem;
            flex: 1;
            width: 100%;
            max-width: 100%;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h2 {
            font-size: 1.5rem;
            color: var(--text-primary);
            font-weight: 700;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .stats-grid {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 1.25rem;
            margin-bottom: 2rem;
            justify-content: flex-start;
            width: 100%;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            animation: fadeUp 0.4s ease both;
            min-width: 180px;
            flex: 1 1 180px;
            max-width: 260px;
        }

        .stat-card:nth-child(1) {animation-delay: 0.05s;}
        .stat-card:nth-child(2) {animation-delay: 0.10s;}
        .stat-card:nth-child(3) {animation-delay: 0.15s;}
        .stat-card:nth-child(4) {animation-delay: 0.20s;}

        @keyframes fadeUp {
            from {opacity: 0; transform: translateY(12px);}
            to {opacity: 1; transform: translateY(0);}
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--accent-light);
            color: var(--accent);
        }

        .stat-icon svg {
            width: 20px;
            height: 20px;
        }

        .stat-icon.success {background: var(--success-light); color: var(--success);}
        .stat-icon.danger {background: var(--danger-light); color: var(--danger);}
        .stat-icon.warning {background: var(--warning-light); color: var(--warning);}

        .stat-label{
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07rem;
            color: var(--text-secondary);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }

        .table-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            animation: fadeUp 0.4s ease 0.25s both;
            width: 100%;
            max-width: 100%;
        }

        .table-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-card-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        thead th {
            background: #f8fafc;
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }

        tbody tr:last-child {border-bottom: none;}
        tbody tr:hover {background: #f8fafc;}

        tbody td {
            padding: 0.85rem 1.5rem;
            color: var(--text-primary);
            vertical-align: middle;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-success {background: var(--success-light); color: var(--success);}
        .badge-danger {background: var(--danger-light); color: var(--danger);}
        .badge-warning {background: var(--warning-light); color: var(--warning);}

        .empty-state {
            padding: 3rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
    </style>
</head>
</html>