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

        /* --- SIDEBAR STYLING --- */
        sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: white;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: bold;
            background-color: #0f172a;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-menu li a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .sidebar-menu li a:hover, .sidebar-menu li a.active {
            color: var(--sidebar-text-hover);
            background-color: rgba(255, 255, 255, 0.05);
            border-left: 4px solid var(--primary-color);
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-logout {
            width: 100%;
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.6rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
        }
        .btn-logout:hover { background: #dc2626; }

        /* --- MAIN CONTENT STYLING --- */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .container {
            padding: 1.5rem 2rem;
            max-width: 100% !important;
            margin: 0;
            width: 100%;
            box-sizing: border-box;
        }

        /* --- KOMPONEN PENDUKUNG LAINNYA --- */
        .card {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.875rem; }
        .form-control { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--border-color); border-radius: 6px; box-sizing: border-box; font-size: 0.875rem; }
        .form-control:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }

        .filter-section form { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .filter-section .form-control { width: auto; min-width: 200px; }

        .btn { display: inline-block; padding: 0.5rem 1rem; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; text-decoration: none; text-align: center; }
        .btn-primary { background-color: var(--primary-color); color: white; }
        .btn-primary:hover { background-color: var(--primary-hover); }
        .btn-secondary { background-color: #64748b; color: white; }
        .btn-success { background-color: #10b981; color: white; }
        .btn-danger { background-color: #ef4444; color: white; }

        .table-container { overflow-x: auto; border-radius: 6px; border: 1px solid var(--border-color); margin-top: 15px; }
        .finance-table { width: 100%; border-collapse: collapse; white-space: nowrap; font-size: 0.8rem; }
        .finance-table th, .finance-table td { padding: 0.6rem 0.75rem; text-align: left; border: 1px solid var(--border-color); }
        .finance-table th { background-color: #f8fafc; font-weight: 600; text-align: center; }
        .finance-table td.angka { text-align: right; }
        .finance-table tbody tr:nth-child(even) { background-color: #f8fafc; }

        .badge { padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .bg-success { background-color: #d1fae5; color: #065f46; }
        .bg-warning { background-color: #fef3c7; color: #92400e; }
    </style>
</head>
<body>

    <!-- SIDEBAR PANEL -->
    <!-- SIDEBAR PANEL -->
    <sidebar style="display: flex; flex-direction: column; height: 100vh; position: sticky; top: 0;">
        <div class="sidebar-brand">
            💰 FINANCE APP
        </div>
        
        <!-- Menu Navigasi (Bisa di-scroll jika menu banyak) -->
        <ul class="sidebar-menu" style="flex-grow: 1; overflow-y: auto;">
            <li><a href="{{ route('uang_masuk.index') }}" class="{{ request()->routeIs('uang_masuk.index') ? 'active' : '' }}">📊 Data Uang Masuk</a></li>
            <li><a href="{{ route('uang_masuk.create') }}" class="{{ request()->routeIs('uang_masuk.create') ? 'active' : '' }}">➕ Tambah Data Baru</a></li>
        </ul>

        <!-- Bagian Bawah Sidebar (User & Logout) -->
        <div class="sidebar-footer" style="padding: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.1); background: #0f172a;">
            @auth
                <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ Auth::user()->email }}">
                    👤 {{ Auth::user()->email }}
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout" style="width: 100%; background: #ef4444; color: white; border: none; padding: 0.5rem; border-radius: 4px; font-weight: 600; cursor: pointer;">
                        🚪 Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" style="display: block; text-align: center; background: #2563eb; color: white; padding: 0.5rem; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
                    🔑 Login
                </a>
            @endauth
        </div>
    </sidebar>

    <!-- MAIN CONTENT AREA -->
    <div class="main-wrapper">
        <header>
            <h3 style="margin: 0; font-size: 1.1rem; color: #334155;">Dashboard Keuangan Klien</h3>
            <span style="font-size: 0.85rem; color: #64748b;">CV. Darma Bakti</span>
        </header>

        <div class="container">
            @yield('content')
        </div>
    </div>

</body>
</html>