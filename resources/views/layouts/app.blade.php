<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DASHBOARD - DARMA APP</title>
<link rel="icon" href="{{ asset('darma.png') }}" type="image/png">
<script>
// Set tema SEBELUM CSS/HTML dirender, biar tidak ada kedipan (flash) warna salah
(function(){
    const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const savedTheme = localStorage.getItem('theme') || (systemPrefersDark ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', savedTheme);
})();
</script>
<style>
/* ========================================================
   AJIAN SAPU JAGAT: PAKSA OVERRIDE INLINE STYLE MODE GELAP 
   ======================================================== */
[data-theme="dark"] .card {
    background-color: var(--bg-card) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
}

[data-theme="dark"] .form-control {
    background-color: var(--bg-body) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
}

[data-theme="dark"] [style*="background: #fff"],
[data-theme="dark"] [style*="background:#fff"],
[data-theme="dark"] [style*="background: #ffffff"],
[data-theme="dark"] [style*="background:#ffffff"],
[data-theme="dark"] [style*="background: white"],
[data-theme="dark"] [style*="background:white"] {
    background-color: var(--bg-card) !important;
    color: var(--text-primary) !important;
}

[data-theme="dark"] [style*="background: #f8fafc"],
[data-theme="dark"] [style*="background:#f8fafc"] {
    background-color: var(--table-header) !important;
    border-color: var(--border-color) !important;
}

[data-theme="dark"] [style*="color: #0f172a"],
[data-theme="dark"] [style*="color:#0f172a"],
[data-theme="dark"] [style*="color: #000"],
[data-theme="dark"] [style*="color:#000"] {
    color: var(--text-primary) !important;
}

/* ===== CSS VARIABLES (Terang & Gelap) ===== */
:root {
  --primary: #2563eb; --primary-h: #1d4ed8; --primary-light: #3b82f6;
  --danger: #dc2626; --danger-h: #b91c1c; 
  --success: #059669; --warning: #d97706;

  --bg-body: #f3f4f6;
  --bg-card: #ffffff;
  --text-primary: #1f2937;
  --text-secondary: #64748b;
  --border-color: #e2e8f0;
  --table-header: #f8fafc;
  --table-hover: #f1f5f9;
  --sidebar: #0f172a;
  --sidebar-2: #1e293b;
}

[data-theme="dark"] {
  --bg-body: #0f172a;
  --bg-card: #1e293b;
  --text-primary: #f8fafc;
  --text-secondary: #94a3b8;
  --border-color: #334155;
  --table-header: #0f172a;
  --table-hover: #334155;
  --sidebar: #020617;
  --sidebar-2: #0f172a;
}

* { box-sizing: border-box; }
body {
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  background: var(--bg-body);
  color: var(--text-primary);
  margin: 0;
  display: flex;
  height: 100vh;
  overflow: hidden;
  transition: background-color 0.3s, color 0.3s;
}

/* ===== SIDEBAR ===== */
.sidebar {
  width: 270px;
  background: linear-gradient(180deg, var(--sidebar) 0%, var(--sidebar-2) 100%);
  color: #fff; display: flex; flex-direction: column;
  flex-shrink: 0; height: 100vh; position: sticky; top: 0;
  box-shadow: 2px 0 12px rgba(0,0,0,.15);
  transition: width .22s ease, margin-left .22s ease;
  overflow: hidden;
}
body.sidebar-collapsed .sidebar { width: 0; margin-left: -1px; }
body.sidebar-collapsed .sidebar * { opacity: 0; pointer-events: none; }

.sidebar-toggle {
  display: flex; align-items: center; justify-content: center;
  width: 36px; height: 36px; border-radius: 8px;
  border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-primary);
  cursor: pointer; transition: .18s ease; flex-shrink: 0;
}
.sidebar-toggle:hover { background: var(--table-hover); border-color: var(--primary); }
.sidebar-toggle svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; }
.header-left { display: flex; align-items: center; gap: 1rem; }

.sidebar-brand {
  padding: 1.5rem 1.5rem 1.25rem;
  display: flex; align-items: center; gap: .75rem;
  border-bottom: 1px solid rgba(255,255,255,.08);
}
.sidebar-brand .logo-mark {
  width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
  background: linear-gradient(135deg, var(--primary-light), var(--primary-h));
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: .95rem; color: #fff;
  box-shadow: 0 4px 10px rgba(37,99,235,.35);
}
.sidebar-brand .brand-text { line-height: 1.2; }
.sidebar-brand .brand-text .brand-name { font-size: .95rem; font-weight: 700; letter-spacing: .02em; color: #fff; }
.sidebar-brand .brand-text .brand-tag { font-size: .68rem; font-weight: 600; letter-spacing: .12em; color: var(--primary-light); text-transform: uppercase; }

.sidebar-section-label {
  padding: 1.1rem 1.5rem .5rem;
  font-size: .68rem; font-weight: 700; letter-spacing: .1em;
  color: var(--text-secondary); text-transform: uppercase;
}

.sidebar-menu { list-style: none; padding: .25rem .75rem; margin: 0; flex-grow: 1; overflow-y: auto; }
.sidebar-menu li { margin-bottom: 2px; }
.sidebar-menu a {
  display: flex; align-items: center; gap: .75rem;
  padding: .65rem .75rem; margin: 0 .25rem;
  color: var(--text-secondary); text-decoration: none;
  font-size: .875rem; font-weight: 500; border-radius: 8px;
  position: relative; transition: .18s ease;
}
.sidebar-menu a svg {
  width: 18px; height: 18px; flex-shrink: 0;
  stroke: currentColor; fill: none; stroke-width: 1.8;
  opacity: .8; transition: .18s ease;
}
.sidebar-menu a:hover { color: #fff; background: rgba(255,255,255,.06); }
.sidebar-menu a:hover svg { opacity: 1; }
.sidebar-menu a.active { color: #fff; background: rgba(37,99,235,.18); }
.sidebar-menu a.active svg { opacity: 1; stroke: var(--primary-light); }
.sidebar-menu a.active::before {
  content: ''; position: absolute; left: -.25rem; top: 50%; transform: translateY(-50%);
  width: 3px; height: 60%; border-radius: 3px; background: var(--primary-light);
}

.sidebar-footer {
  padding: 1rem 1.25rem 1.25rem;
  border-top: 1px solid rgba(255,255,255,.08);
  background: rgba(0,0,0,.15);
}
.user-card { display: flex; align-items: center; gap: .65rem; margin-bottom: .85rem; }
.user-avatar {
  width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, var(--primary-light), var(--primary-h));
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: .8rem; color: #fff;
}
.user-email { font-size: .75rem; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.btn-logout, .btn-login-sidebar {
  width: 100%; display: flex; align-items: center; justify-content: center; gap: .5rem;
  border: none; padding: .6rem; border-radius: 8px; font-weight: 600; font-size: .83rem;
  cursor: pointer; text-decoration: none; color: #fff; transition: .18s ease;
}
.btn-logout { background: rgba(220,38,38,.15); border: 1px solid rgba(220,38,38,.4); color: #fca5a5; }
.btn-logout:hover { background: var(--danger); border-color: var(--danger); color: #fff; }
.btn-login-sidebar { background: var(--primary); } 
.btn-login-sidebar:hover { background: var(--primary-h); }
.btn-logout svg, .btn-login-sidebar svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 1.8; }

/* ===== MAIN ===== */
.main-wrapper { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; background: var(--bg-body); transition: background-color 0.3s; }
.main-header {
  background: var(--bg-card); padding: 1rem 2rem; border-bottom: 1px solid var(--border-color);
  display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;
  transition: background-color 0.3s, border-color 0.3s;
}
.main-header h3 { margin: 0; font-size: 1.1rem; color: var(--text-primary); font-weight: 600; }
.company-name { font-size: .85rem; color: var(--text-secondary); }
.container { padding: 1.5rem 2rem; width: 100%; flex-grow: 1; }

.card {
  background: var(--bg-card); border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.06); padding: 1.5rem;
  margin-bottom: 1.5rem; border: 1px solid var(--border-color);
  transition: background-color 0.3s, border-color 0.3s, box-shadow 0.3s;
}
[data-theme="dark"] .card { box-shadow: 0 1px 3px rgba(0,0,0,.35); }

::-webkit-scrollbar { width: 10px; height: 10px; }
::-webkit-scrollbar-track { background: var(--bg-body); }
::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 8px; }
::-webkit-scrollbar-thumb:hover { background: var(--text-secondary); }
.form-group { margin-bottom: 1rem; }
label { display: block; margin-bottom: .5rem; font-weight: 500; font-size: .875rem; color: var(--text-primary); }
.form-control {
  width: 100%; padding: .5rem .75rem; border: 1px solid var(--border-color); border-radius: 6px;
  font-size: .875rem; transition: .2s; background: var(--bg-body); color: var(--text-primary);
}
.form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.filter-section form { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
.filter-section .form-control { width: auto; min-width: 200px; }

.btn {
  display: inline-block; padding: .5rem 1.25rem; border: none; border-radius: 6px; font-weight: 500;
  font-size: .875rem; cursor: pointer; text-decoration: none; text-align: center; transition: .2s; color: #fff;
}
.btn-primary { background: var(--primary); } .btn-primary:hover { background: var(--primary-h); }
.btn-secondary { background: #64748b; } .btn-secondary:hover { background: #475569; }
.btn-success { background: var(--success); } .btn-success:hover { background: #047857; }
.btn-danger { background: var(--danger); } .btn-danger:hover { background: var(--danger-h); }

.btn-theme-toggle {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: 0.2s ease;
}
.btn-theme-toggle:hover { background: var(--table-hover); }

.table-container { overflow-x: auto; border-radius: 6px; border: 1px solid var(--border-color); margin-top: 15px; }
.finance-table { width: 100%; border-collapse: collapse; white-space: nowrap; font-size: .8rem; }
.finance-table th, .finance-table td { padding: .6rem .75rem; text-align: left; border: 1px solid var(--border-color); color: var(--text-primary); }
.finance-table th { background: var(--table-header); font-weight: 600; text-align: center; color: var(--text-primary); }
.finance-table td.angka { text-align: right; }
.finance-table tbody tr:nth-child(even) { background: var(--table-header); }
.finance-table tbody tr:hover { background: var(--table-hover); }

.badge { padding: .25rem .75rem; border-radius: 9999px; font-size: .7rem; font-weight: 600; display: inline-block; transition: .3s ease; }
.badge-success { background: #d1fae5; color: #065f46; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-danger { background: #fee2e2; color: #991b1b; }

[data-theme="dark"] .badge-success { background: rgba(5,150,105,.2); color: #6ee7b7; }
[data-theme="dark"] .badge-warning { background: rgba(217,119,6,.2); color: #fcd34d; }
[data-theme="dark"] .badge-danger { background: rgba(220,38,38,.2); color: #fca5a5; }

.pagination-wrapper { margin-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; padding: 10px 0; }
.pagination-info { font-size: .85rem; color: var(--text-secondary); }
.pagination-links { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
.pagination-links a, .pagination-links span { padding: 6px 12px; border-radius: 4px; font-size: .85rem; text-decoration: none; transition: .2s; }
.pagination-links a { background: var(--bg-card); color: var(--text-primary); border: 1px solid var(--border-color); }
.pagination-links a:hover { background: var(--table-hover); border-color: var(--text-secondary); }
.pagination-links .active { background: var(--primary); color: #fff; border: 1px solid var(--primary); font-weight: 600; }
.pagination-links .disabled { background: var(--bg-body); color: var(--text-secondary); border: 1px solid var(--border-color); cursor: not-allowed; }
.pagination-links .dots { background: none; border: none; color: var(--text-secondary); padding: 6px 8px; }

@media (max-width:768px){
  .sidebar { width: 220px; }
  .container { padding: 1rem; }
  .main-header { padding: .75rem 1rem; flex-direction: column; align-items: flex-start; gap: 10px; }
  .filter-section .form-control { min-width: 150px; }
  .pagination-wrapper { flex-direction: column; align-items: flex-start; }
}
@media (max-width:576px){
  body { flex-direction: column; }
  .sidebar { width: 100%; height: auto; position: relative; }
  .sidebar-menu { display: flex; flex-wrap: wrap; padding: .5rem; }
  .sidebar-menu a { padding: .5rem .75rem; }
  .sidebar-menu a.active::before { display: none; }
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

    <div class="sidebar-section-label">Finance</div>
    <ul class="sidebar-menu">
        @php
            $userPerms = Auth::user()->permissions ?? [];
        @endphp

        <!-- MENU DATA REPORT -->
        @if(Auth::user()->role == 'admin' || in_array('finance_report', $userPerms))
        <li>
            <a href="{{ route('finance.report') }}" class="{{ request()->routeIs('finance.report') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M3 3v18h18" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 15l3.5-4 3 3L19 8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Data Report
            </a>
        </li>
        @endif

        <!-- MENU UANG MASUK PEMERINTAH -->
        @if(Auth::user()->role == 'admin' || in_array('uang_masuk_pemerintah', $userPerms))
        <li>
            <a href="{{ route('uang_masuk.index', ['kategori' => 'pemerintah']) }}" class="{{ request('kategori') != 'swasta' && request()->routeIs('uang_masuk.index') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M4 21h16" stroke-linecap="round"/><path d="M5 21V10l7-6 7 6v11" stroke-linejoin="round"/><path d="M9 21v-6h6v6" stroke-linejoin="round"/></svg>
                Uang Masuk Pemerintah
            </a>
        </li>
        @endif

        <!-- MENU UANG MASUK SWASTA -->
        @if(Auth::user()->role == 'admin' || in_array('uang_masuk_swasta', $userPerms))
        <li>
            <a href="{{ route('uang_masuk.index', ['kategori' => 'swasta']) }}" class="{{ request('kategori') == 'swasta' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Uang Masuk Swasta
            </a>
        </li>
        @endif

        <!-- MENU CETAK INVOICE (DIPERBOLEHKAN JIKA ADMIN ATAU ADA PERMISSION) -->
        @if(Auth::user()->role == 'admin' || in_array('invoice_index', $userPerms))
        <div class="sidebar-section-label">Invoice</div>
        <li>
            <a href="{{ route('invoice.index') }}" class="{{ request()->routeIs('invoice.index*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Cetak Invoice
            </a>
        </li>
        @endif

        <!-- MENU MASTER DATA BARANG (DIPERBOLEHKAN JIKA ADMIN ATAU ADA PERMISSION) -->
        @if(Auth::user()->role == 'admin' || in_array('barang_index', $userPerms))
        <li>
            <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M20 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 2 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 20 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                Master Data Barang
            </a>
        </li>
        @endif

        <!-- MENU KHUSUS ADMIN SAJA (TAMBAH UANG MASUK, REKENING, DOKUMEN, & MANAJEMEN USER) -->
        @if(Auth::check() && Auth::user()->role == 'admin')
            <div class="sidebar-section-label">Admin Control</div>
            <li>
                <a href="{{ route('uang_masuk.create') }}" class="{{ request()->routeIs('uang_masuk.create') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8" stroke-linecap="round"/></svg>
                    Tambah Uang Masuk
                </a>
            </li>
            <li>
                <a href="{{ route('rekening.index') }}" class="{{ request()->routeIs('rekening*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M21 4H3a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="1" y1="10" x2="23" y2="10"></line><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    Master Data Rekening
                </a>
            </li>
            <div class="sidebar-section-label">Dokumen</div>
            <li>
                <a href="{{ route('dokumen-api.index') }}" class="{{ request()->routeIs('dokumen-api*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/><path d="M8 12h8"/><path d="M8 16h5"/></svg>
                    Data Dokumen Perusahaan
                </a>
            </li>
            <div class="sidebar-section-label">Pengaturan</div>
            <li>
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Manajemen User
                </a>
            </li>
        @endif
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
        
        <div style="display: flex; align-items: center; gap: 15px;">
            <button type="button" class="btn-theme-toggle" onclick="toggleDarkMode()" id="btnTheme">
                🌙 Mode Malam
            </button>
            <span class="company-name">Skykom CopyRight</span>
        </div>
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

    if (localStorage.getItem(STORAGE_KEY) === '1') {
        body.classList.add('sidebar-collapsed');
    }

    toggleBtn.addEventListener('click', function(){
        body.classList.toggle('sidebar-collapsed');
        localStorage.setItem(STORAGE_KEY, body.classList.contains('sidebar-collapsed') ? '1' : '0');
    });
})();

(function(){
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    updateButtonText(currentTheme);
})();

function toggleDarkMode() {
    let currentTheme = document.documentElement.getAttribute('data-theme');
    let newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateButtonText(newTheme);
}

function updateButtonText(theme) {
    const btn = document.getElementById('btnTheme');
    if (btn) {
        btn.innerHTML = theme === 'dark' ? '☀️ Mode Terang' : '🌙 Mode Malam';
    }
}
</script>
</body>
</html>