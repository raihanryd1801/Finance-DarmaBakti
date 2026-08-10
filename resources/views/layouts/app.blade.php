<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Finance - Uang Masuk</title>
<style>
:root{--primary:#2563eb;--primary-h:#1d4ed8;--primary-light:#3b82f6;--bg:#f3f4f6;
--sidebar:#0f172a;--sidebar-2:#1e293b;--muted:#94a3b8;--muted-2:#64748b;
--border:#e2e8f0;--danger:#dc2626;--danger-h:#b91c1c;--success:#059669;--warning:#d97706}
*{box-sizing:border-box}
body{font-family:'Segoe UI',system-ui,-apple-system,sans-serif;background:var(--bg);color:#1f2937;
margin:0;display:flex;height:100vh;overflow:hidden}

/* ===== SIDEBAR ===== */
.sidebar{
  width:270px;
  background:linear-gradient(180deg,var(--sidebar) 0%,var(--sidebar-2) 100%);
  color:#fff;display:flex;flex-direction:column;
  flex-shrink:0;height:100vh;position:sticky;top:0;
  box-shadow:2px 0 12px rgba(0,0,0,.15);
  transition:width .22s ease, margin-left .22s ease;
  overflow:hidden;
}
body.sidebar-collapsed .sidebar{width:0;margin-left:-1px}
body.sidebar-collapsed .sidebar *{opacity:0;pointer-events:none}

/* Tombol untuk buka/tutup sidebar */
.sidebar-toggle{
  display:flex;align-items:center;justify-content:center;
  width:36px;height:36px;border-radius:8px;
  border:1px solid var(--border);background:#fff;color:#334155;
  cursor:pointer;transition:.18s ease;flex-shrink:0;
}
.sidebar-toggle:hover{background:#f1f5f9;border-color:#cbd5e1}
.sidebar-toggle svg{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:1.8}
.header-left{display:flex;align-items:center;gap:1rem}

.sidebar-brand{
  padding:1.5rem 1.5rem 1.25rem;
  display:flex;align-items:center;gap:.75rem;
  border-bottom:1px solid rgba(255,255,255,.08);
}
.sidebar-brand .logo-mark{
  width:38px;height:38px;border-radius:10px;flex-shrink:0;
  background:linear-gradient(135deg,var(--primary-light),var(--primary-h));
  display:flex;align-items:center;justify-content:center;
  font-weight:700;font-size:.95rem;color:#fff;
  box-shadow:0 4px 10px rgba(37,99,235,.35);
}
.sidebar-brand .brand-text{line-height:1.2}
.sidebar-brand .brand-text .brand-name{
  font-size:.95rem;font-weight:700;letter-spacing:.02em;color:#fff;
}
.sidebar-brand .brand-text .brand-tag{
  font-size:.68rem;font-weight:600;letter-spacing:.12em;color:var(--primary-light);
  text-transform:uppercase;
}

.sidebar-section-label{
  padding:1.1rem 1.5rem .5rem;
  font-size:.68rem;font-weight:700;letter-spacing:.1em;
  color:var(--muted-2);text-transform:uppercase;
}

.sidebar-menu{list-style:none;padding:.25rem .75rem;margin:0;flex-grow:1;overflow-y:auto}
.sidebar-menu li{margin-bottom:2px}
.sidebar-menu a{
  display:flex;align-items:center;gap:.75rem;
  padding:.65rem .75rem;margin:0 .25rem;
  color:var(--muted);text-decoration:none;
  font-size:.875rem;font-weight:500;border-radius:8px;
  position:relative;transition:.18s ease;
}
.sidebar-menu a svg{
  width:18px;height:18px;flex-shrink:0;
  stroke:currentColor;fill:none;stroke-width:1.8;
  opacity:.8;transition:.18s ease;
}
.sidebar-menu a:hover{color:#fff;background:rgba(255,255,255,.06)}
.sidebar-menu a:hover svg{opacity:1}
.sidebar-menu a.active{
  color:#fff;background:rgba(37,99,235,.18);
}
.sidebar-menu a.active svg{opacity:1;stroke:var(--primary-light)}
.sidebar-menu a.active::before{
  content:'';position:absolute;left:-.25rem;top:50%;transform:translateY(-50%);
  width:3px;height:60%;border-radius:3px;background:var(--primary-light);
}

.sidebar-footer{
  padding:1rem 1.25rem 1.25rem;
  border-top:1px solid rgba(255,255,255,.08);
  background:rgba(0,0,0,.15);
}
.user-card{display:flex;align-items:center;gap:.65rem;margin-bottom:.85rem}
.user-avatar{
  width:34px;height:34px;border-radius:50%;flex-shrink:0;
  background:linear-gradient(135deg,var(--primary-light),var(--primary-h));
  display:flex;align-items:center;justify-content:center;
  font-weight:700;font-size:.8rem;color:#fff;
}
.user-email{font-size:.75rem;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.btn-logout,.btn-login-sidebar{
  width:100%;display:flex;align-items:center;justify-content:center;gap:.5rem;
  border:none;padding:.6rem;border-radius:8px;font-weight:600;font-size:.83rem;
  cursor:pointer;text-decoration:none;color:#fff;transition:.18s ease;
}
.btn-logout{background:rgba(220,38,38,.15);border:1px solid rgba(220,38,38,.4);color:#fca5a5}
.btn-logout:hover{background:var(--danger);border-color:var(--danger);color:#fff}
.btn-login-sidebar{background:var(--primary)} .btn-login-sidebar:hover{background:var(--primary-h)}
.btn-logout svg,.btn-login-sidebar svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:1.8}

/* ===== MAIN ===== */
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
  .sidebar{width:220px}
  .container{padding:1rem}
  .main-header{padding:.75rem 1rem;flex-direction:column;align-items:flex-start;gap:5px}
  .filter-section .form-control{min-width:150px}
  .pagination-wrapper{flex-direction:column;align-items:flex-start}
}
@media (max-width:576px){
  body{flex-direction:column}
  .sidebar{width:100%;height:auto;position:relative}
  .sidebar-menu{display:flex;flex-wrap:wrap;padding:.5rem}
  .sidebar-menu a{padding:.5rem .75rem}
  .sidebar-menu a.active::before{display:none}
}
</style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo-mark">CV</div>
        <div class="brand-text">
            <div class="brand-name">DARMA BAKTI</div>
            <div class="brand-tag">App</div>
        </div>
    </div>

    <div class="sidebar-section-label">Menu</div>
    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('finance.report') }}" class="{{ request()->routeIs('finance.report') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M3 3v18h18" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 15l3.5-4 3 3L19 8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Data Report
            </a>
        </li>
        <li>
            <a href="{{ route('uang_masuk.index', ['kategori' => 'pemerintah']) }}" class="{{ request('kategori') != 'swasta' && request()->routeIs('uang_masuk.index') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M4 21h16" stroke-linecap="round"/><path d="M5 21V10l7-6 7 6v11" stroke-linejoin="round"/><path d="M9 21v-6h6v6" stroke-linejoin="round"/></svg>
                Uang Masuk Pemerintah
            </a>
        </li>
        <li>
            <a href="{{ route('uang_masuk.index', ['kategori' => 'swasta']) }}" class="{{ request('kategori') == 'swasta' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Uang Masuk Swasta
            </a>
        </li>
        <li>
            <a href="{{ route('uang_masuk.create') }}" class="{{ request()->routeIs('uang_masuk.create') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8" stroke-linecap="round"/></svg>
                Tambah Uang Masuk
            </a>
        </li>
        <!-- MENU DOKUMEN API -->
        <li>
            <a href="{{ route('dokumen-api.index') }}" class="{{ request()->routeIs('dokumen-api*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Data Dokumen Perusahaan
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        @auth
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->email, 0, 1)) }}</div>
                <div class="user-email" title="{{ Auth::user()->email }}">{{ Auth::user()->email }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 17l5-5-5-5M21 12H9" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-login-sidebar">
                <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 17l5-5-5-5M15 12H3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Login
            </a>
        @endauth
    </div>
</aside>

<div class="main-wrapper">
    <header class="main-header">
        <div class="header-left">
            <button type="button" class="sidebar-toggle" id="sidebarToggle" title="Sembunyikan / tampilkan panel">
                <svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18" stroke-linecap="round"/></svg>
            </button>
            <h3>Dashboard</h3>
        </div>
        <span class="company-name">Skykom CopyRight</span>
    </header>
    <div class="container">
        @yield('content')
    </div>
</div>

<script>
(function(){
    var body = document.body;
    var toggleBtn = document.getElementById('sidebarToggle');
    var STORAGE_KEY = 'sidebar-collapsed';

    // Terapkan status tersimpan sebelumnya saat halaman dibuka
    if (localStorage.getItem(STORAGE_KEY) === '1') {
        body.classList.add('sidebar-collapsed');
    }

    toggleBtn.addEventListener('click', function(){
        body.classList.toggle('sidebar-collapsed');
        localStorage.setItem(STORAGE_KEY, body.classList.contains('sidebar-collapsed') ? '1' : '0');
    });
})();
</script>
</body>
</html>