<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Finance - Uang Masuk</title>
<style>
:root{--primary:#2563eb;--primary-h:#1d4ed8;--bg:#f3f4f6;--sidebar:#1e293b;--muted:#94a3b8;
--border:#e2e8f0;--danger:#dc2626;--danger-h:#b91c1c;--success:#059669;--warning:#d97706}
*{box-sizing:border-box}
body{font-family:'Segoe UI',system-ui,-apple-system,sans-serif;background:var(--bg);color:#1f2937;
margin:0;display:flex;height:100vh;overflow:hidden}

.sidebar{width:260px;background:var(--sidebar);color:#fff;display:flex;flex-direction:column;
flex-shrink:0;height:100vh;position:sticky;top:0}
.sidebar-brand{padding:1.5rem;font-size:1.25rem;font-weight:600;background:#0f172a}
.sidebar-brand span{color:var(--primary)}
.sidebar-menu{list-style:none;padding:1rem 0;margin:0;flex-grow:1;overflow-y:auto}
.sidebar-menu a{display:block;padding:.75rem 1.5rem;color:var(--muted);text-decoration:none;
font-size:.9rem;border-left:3px solid transparent;transition:.2s}
.sidebar-menu a:hover,.sidebar-menu a.active{color:#fff;background:rgba(255,255,255,.07);border-left-color:var(--primary)}
.sidebar-footer{padding:1rem 1.5rem;border-top:1px solid rgba(255,255,255,.08);background:#0f172a}
.user-email{font-size:.75rem;color:var(--muted);margin-bottom:.75rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.btn-logout,.btn-login-sidebar{width:100%;display:block;text-align:center;border:none;padding:.6rem;
border-radius:6px;font-weight:600;font-size:.85rem;cursor:pointer;text-decoration:none;color:#fff;transition:.2s}
.btn-logout{background:var(--danger)} .btn-logout:hover{background:var(--danger-h)}
.btn-login-sidebar{background:var(--primary)} .btn-login-sidebar:hover{background:var(--primary-h)}

.main-wrapper{flex-grow:1;display:flex;flex-direction:column;overflow-y:auto;background:var(--bg)}
.main-header{background:#fff;padding:1rem 2rem;border-bottom:1px solid var(--border);
display:flex;justify-content:space-between;align-items:center;flex-shrink:0}
.main-header h3{margin:0;font-size:1.1rem;color:#334155;font-weight:600}
.company-name{font-size:.85rem;color:#64748b}
.container{padding:1.5rem 2rem;width:100%;flex-grow:1}

.card{background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.06);padding:1.5rem;
margin-bottom:1.5rem;border:1px solid var(--border)}
.form-group{margin-bottom:1rem}
label{display:block;margin-bottom:.5rem;font-weight:500;font-size:.875rem;color:#374151}
.form-control{width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;
font-size:.875rem;transition:.2s}
.form-control:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.filter-section form{display:flex;flex-wrap:wrap;gap:10px;align-items:center}
.filter-section .form-control{width:auto;min-width:200px}

.btn{display:inline-block;padding:.5rem 1.25rem;border:none;border-radius:6px;font-weight:500;
font-size:.875rem;cursor:pointer;text-decoration:none;text-align:center;transition:.2s;color:#fff}
.btn-primary{background:var(--primary)} .btn-primary:hover{background:var(--primary-h)}
.btn-secondary{background:#64748b} .btn-secondary:hover{background:#475569}
.btn-success{background:var(--success)} .btn-success:hover{background:#047857}
.btn-danger{background:var(--danger)} .btn-danger:hover{background:var(--danger-h)}

.table-container{overflow-x:auto;border-radius:6px;border:1px solid var(--border);margin-top:15px}
.finance-table{width:100%;border-collapse:collapse;white-space:nowrap;font-size:.8rem}
.finance-table th,.finance-table td{padding:.6rem .75rem;text-align:left;border:1px solid var(--border)}
.finance-table th{background:#f8fafc;font-weight:600;text-align:center;color:#1e293b}
.finance-table td.angka{text-align:right}
.finance-table tbody tr:nth-child(even){background:#f8fafc}
.finance-table tbody tr:hover{background:#f1f5f9}

.badge{padding:.25rem .75rem;border-radius:9999px;font-size:.7rem;font-weight:600;display:inline-block}
.badge-success{background:#d1fae5;color:#065f46}
.badge-warning{background:#fef3c7;color:#92400e}
.badge-danger{background:#fee2e2;color:#991b1b}

.pagination-wrapper{margin-top:1.5rem;display:flex;justify-content:space-between;align-items:center;
flex-wrap:wrap;gap:15px;padding:10px 0}
.pagination-info{font-size:.85rem;color:#6b7280}
.pagination-links{display:flex;align-items:center;gap:4px;flex-wrap:wrap}
.pagination-links a,.pagination-links span{padding:6px 12px;border-radius:4px;font-size:.85rem;text-decoration:none;transition:.2s}
.pagination-links a{background:#fff;color:#374151;border:1px solid #d1d5db}
.pagination-links a:hover{background:#f3f4f6;border-color:#9ca3af}
.pagination-links .active{background:var(--primary);color:#fff;border:1px solid var(--primary);font-weight:600}
.pagination-links .disabled{background:#e5e7eb;color:#9ca3af;border:1px solid #e5e7eb;cursor:not-allowed}
.pagination-links .dots{background:none;border:none;color:#6b7280;padding:6px 8px}

@media (max-width:768px){
  .sidebar{width:200px}
  .container{padding:1rem}
  .main-header{padding:.75rem 1rem;flex-direction:column;align-items:flex-start;gap:5px}
  .filter-section .form-control{min-width:150px}
  .pagination-wrapper{flex-direction:column;align-items:flex-start}
}
@media (max-width:576px){
  body{flex-direction:column}
  .sidebar{width:100%;height:auto;position:relative}
  .sidebar-menu{display:flex;flex-wrap:wrap;padding:.5rem}
  .sidebar-menu a{padding:.5rem 1rem;border-left:none;border-bottom:2px solid transparent}
  .sidebar-menu a:hover,.sidebar-menu a.active{border-left:none;border-bottom-color:var(--primary)}
}
</style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">FINANCE <span>APP</span></div>
    <ul class="sidebar-menu">
        <li><a href="{{ route('finance.report') }}" class="{{ request()->routeIs('finance.report') ? 'active' : '' }}">Data Report</a></li>
        <li><a href="{{ route('uang_masuk.create') }}" class="{{ request()->routeIs('uang_masuk.create') ? 'active' : '' }}">Tambah Data Baru</a></li>
        <li><a href="{{ route('uang_masuk.index', ['kategori' => 'pemerintah']) }}" class="{{ request('kategori') != 'swasta' && request()->routeIs('uang_masuk.index') ? 'active' : '' }}">Uang Masuk Pemerintah</a></li>
            <li><a href="{{ route('uang_masuk.index', ['kategori' => 'swasta']) }}" class="{{ request('kategori') == 'swasta' ? 'active' : '' }}">Uang Masuk Swasta</a></li>
    </ul>
    <div class="sidebar-footer">
        @auth
            <div class="user-email" title="{{ Auth::user()->email }}">{{ Auth::user()->email }}</div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-login-sidebar">Login</a>
        @endauth
    </div>
</aside>

<div class="main-wrapper">
    <header class="main-header">
        <h3>Dashboard Keuangan</h3>
        <span class="company-name">CV. Darma Bakti</span>
    </header>
    <div class="container">
        @yield('content')
    </div>
</div>

</body>
</html>