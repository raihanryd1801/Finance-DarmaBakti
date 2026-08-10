<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Finance - Uang Masuk</title>
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-color: #f3f4f6;
            --sidebar-bg: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-text-hover: #ffffff;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --danger-color: #dc2626;
            --danger-hover: #b91c1c;
            --success-color: #059669;
            --warning-color: #d97706;
        }
        
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            color: #1f2937;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* SIDEBAR STYLING */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: white;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            height: 100vh;
            position: sticky;
            top: 0;
        }

        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: 600;
            background-color: #0f172a;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-brand span {
            color: var(--primary-color);
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-menu li a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu li a:hover {
            color: var(--sidebar-text-hover);
            background-color: rgba(255, 255, 255, 0.05);
            border-left-color: var(--primary-color);
        }

        .sidebar-menu li a.active {
            color: var(--sidebar-text-hover);
            background-color: rgba(255, 255, 255, 0.08);
            border-left-color: var(--primary-color);
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background-color: #0f172a;
        }

        .sidebar-footer .user-email {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-bottom: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-logout {
            width: 100%;
            background-color: var(--danger-color);
            color: white;
            border: none;
            padding: 0.6rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
            font-size: 0.85rem;
        }

        .btn-logout:hover {
            background-color: var(--danger-hover);
        }

        .btn-login-sidebar {
            display: block;
            text-align: center;
            background-color: var(--primary-color);
            color: white;
            padding: 0.6rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .btn-login-sidebar:hover {
            background-color: var(--primary-hover);
        }

        /* MAIN CONTENT STYLING */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            background-color: var(--bg-color);
        }

        .main-header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .main-header h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #334155;
            font-weight: 600;
        }

        .main-header .company-name {
            font-size: 0.85rem;
            color: #64748b;
        }

        .container {
            padding: 1.5rem 2rem;
            max-width: 100%;
            margin: 0;
            width: 100%;
            box-sizing: border-box;
            flex-grow: 1;
        }

        /* KOMPONEN PENDUKUNG */
        .card {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 0.875rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .filter-section form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .filter-section .form-control {
            width: auto;
            min-width: 200px;
        }

        /* BUTTONS */
        .btn {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #475569;
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background-color: #047857;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
        }

        /* TABLE */
        .table-container {
            overflow-x: auto;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            margin-top: 15px;
        }

        .finance-table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
            font-size: 0.8rem;
        }

        .finance-table th,
        .finance-table td {
            padding: 0.6rem 0.75rem;
            text-align: left;
            border: 1px solid var(--border-color);
        }

        .finance-table th {
            background-color: #f8fafc;
            font-weight: 600;
            text-align: center;
            color: #1e293b;
        }

        .finance-table td.angka {
            text-align: right;
        }

        .finance-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .finance-table tbody tr:hover {
            background-color: #f1f5f9;
        }

        /* BADGE */
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* PAGINATION */
        .pagination-wrapper {
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            padding: 10px 0;
        }

        .pagination-info {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .pagination-links {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }

        .pagination-links a,
        .pagination-links span {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination-links a {
            background: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .pagination-links a:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .pagination-links .active {
            background: var(--primary-color);
            color: white;
            border: 1px solid var(--primary-color);
            font-weight: 600;
        }

        .pagination-links .disabled {
            background: #e5e7eb;
            color: #9ca3af;
            border: 1px solid #e5e7eb;
            cursor: not-allowed;
        }

        .pagination-links .dots {
            background: transparent;
            border: none;
            color: #6b7280;
            padding: 6px 8px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .container {
                padding: 1rem;
            }

            .main-header {
                padding: 0.75rem 1rem;
                flex-direction: column;
                gap: 5px;
                align-items: flex-start;
            }

            .filter-section .form-control {
                min-width: 150px;
            }

            .pagination-wrapper {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 576px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar-menu {
                display: flex;
                flex-wrap: wrap;
                padding: 0.5rem;
            }

            .sidebar-menu li a {
                padding: 0.5rem 1rem;
                border-left: none;
                border-bottom: 2px solid transparent;
            }

            .sidebar-menu li a:hover,
            .sidebar-menu li a.active {
                border-left: none;
                border-bottom-color: var(--primary-color);
            }

            .sidebar-footer {
                border-top: 1px solid rgba(255, 255, 255, 0.08);
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR PANEL -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            FINANCE <span>APP</span>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('uang_masuk.index') }}" class="{{ request()->routeIs('uang_masuk.index') ? 'active' : '' }}">
                    Data Uang Masuk
                </a>
            </li>
            <li>
                <a href="{{ route('uang_masuk.create') }}" class="{{ request()->routeIs('uang_masuk.create') ? 'active' : '' }}">
                    Tambah Data Baru
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            @auth
                <div class="user-email" title="{{ Auth::user()->email }}">
                    {{ Auth::user()->email }}
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-login-sidebar">
                    Login
                </a>
            @endauth
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="main-wrapper">
        <header class="main-header">
            <h3>Dashboard Keuangan Klien</h3>
            <span class="company-name">CV. Darma Bakti</span>
        </header>

        <div class="container">
            @yield('content')
        </div>
    </div>

</body>
</html>