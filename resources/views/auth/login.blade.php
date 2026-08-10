<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Finance</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink: #0b1220;
            --ink-soft: #16223a;
            --line: #24314f;
            --accent: #2563eb;
            --accent-dark: #1d4ed8;
            --paper: #ffffff;
            --paper-soft: #f4f6f9;
            --border: #e2e8f0;
            --text: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            background: var(--paper-soft);
        }

        .login-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
        }

        /* ===== PANEL KIRI - BRAND / LEDGER PREVIEW ===== */
        .brand-panel {
            position: relative;
            background: linear-gradient(180deg, var(--ink) 0%, var(--ink-soft) 100%);
            padding: 3rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        /* pola garis tipis ala kertas ledger */
        .brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(
                to bottom,
                transparent 0,
                transparent 38px,
                rgba(255, 255, 255, 0.04) 39px
            );
            pointer-events: none;
        }

        .brand-mark {
            font-size: 1.3rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
            position: relative;
        }
        .brand-mark span { color: #60a5fa; }

        .brand-tagline {
            margin-top: 0.9rem;
            font-size: 0.9rem;
            line-height: 1.6;
            color: #94a3b8;
            max-width: 320px;
            position: relative;
        }

        /* ===== ANIMASI GRAFIK KEUANGAN (ABSTRAK, TANPA DATA ASLI) ===== */
        .chart-preview {
            position: relative;
            margin-top: 2.5rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1.5rem 1.25rem 1.25rem;
        }

        .chart-preview .chart-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .chart-svg { display: block; width: 100%; height: auto; overflow: visible; }

        /* garis tren ditarik dari 0 ke panjang penuh */
        .chart-line {
            fill: none;
            stroke: #60a5fa;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 480;
            stroke-dashoffset: 480;
            animation: drawLine 2.2s ease-out forwards;
        }

        .chart-area {
            opacity: 0;
            animation: fadeArea 1s ease-out 1.4s forwards;
        }

        .chart-dot {
            opacity: 0;
            animation: popDot 0.4s ease-out forwards;
        }
        .chart-dot:nth-of-type(1) { animation-delay: 0.5s; }
        .chart-dot:nth-of-type(2) { animation-delay: 1.05s; }
        .chart-dot:nth-of-type(3) { animation-delay: 1.6s; }
        .chart-dot:nth-of-type(4) { animation-delay: 2.1s; }

        .chart-grid {
            stroke: rgba(255, 255, 255, 0.06);
            stroke-width: 1;
        }

        .chart-pulse {
            fill: #60a5fa;
            opacity: 0.35;
            animation: pulse 2.4s ease-in-out infinite;
            animation-delay: 2.2s;
            transform-origin: center;
        }

        @keyframes drawLine {
            to { stroke-dashoffset: 0; }
        }
        @keyframes fadeArea {
            to { opacity: 1; }
        }
        @keyframes popDot {
            to { opacity: 1; }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.35; }
            50% { transform: scale(2.4); opacity: 0; }
        }

        .brand-foot {
            position: relative;
            font-family: 'Consolas', 'SFMono-Regular', monospace;
            font-size: 0.72rem;
            color: #475569;
            letter-spacing: 0.5px;
        }

        /* ===== PANEL KANAN - FORM ===== */
        .form-panel {
            background: var(--paper);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 2rem;
        }

        .form-panel-inner {
            width: 100%;
            max-width: 360px;
        }

        .section-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 0.6rem;
        }

        .greeting {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.4rem;
        }

        .lead {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 1.75rem;
        }

        .form-group { margin-bottom: 1.1rem; }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .form-control {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
            background: var(--paper-soft);
            color: var(--text);
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
            background: #ffffff;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 1.6rem;
        }
        .form-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .form-check label {
            font-size: 0.85rem;
            color: #475569;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-login:hover { background: var(--accent-dark); }
        .btn-login:focus-visible {
            outline: 2px solid var(--accent-dark);
            outline-offset: 2px;
        }

        .demo-hint {
            margin-top: 1.5rem;
            padding: 0.85rem 1rem;
            border-radius: 8px;
            background: var(--paper-soft);
            border: 1px solid var(--border);
            font-size: 0.78rem;
            color: var(--text-muted);
            line-height: 1.6;
        }
        .demo-hint strong { color: var(--text); font-weight: 600; }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            border-left: 4px solid #dc2626;
        }

        @media (prefers-reduced-motion: reduce) {
            .chart-line, .chart-area, .chart-dot { animation: none; opacity: 1; stroke-dashoffset: 0; }
            .chart-pulse { animation: none; opacity: 0; }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 860px) {
            .login-shell { grid-template-columns: 1fr; }
            .brand-panel {
                padding: 2rem 1.75rem;
            }
            .chart-preview { display: none; }
            .brand-tagline { max-width: none; }
        }

        @media (max-width: 480px) {
            .form-panel { padding: 2rem 1.5rem; }
            .greeting { font-size: 1.3rem; }
        }
    </style>
</head>
<body>
    <div class="login-shell">

        <!-- PANEL KIRI: BRAND + LEDGER PREVIEW -->
        <aside class="brand-panel">
            <div>
                <div class="brand-mark">CV. DARMA BAKTI <span>APP</span></div>
                <p class="brand-tagline">Satu dasbor untuk memantau uang masuk, status transfer, rekapitulasi per instansi & Monitoring Dokument</p>

                <div class="chart-preview" aria-hidden="true">
                    <div class="chart-label">Arus Kas &middot;</div>
                    <svg class="chart-svg" viewBox="0 0 300 110" xmlns="http://www.w3.org/2000/svg">
                        <!-- garis grid horizontal -->
                        <line class="chart-grid" x1="0" y1="20" x2="300" y2="20" />
                        <line class="chart-grid" x1="0" y1="55" x2="300" y2="55" />
                        <line class="chart-grid" x1="0" y1="90" x2="300" y2="90" />

                        <!-- area di bawah garis tren -->
                        <path class="chart-area" fill="url(#chartGradient)"
                              d="M0,80 C40,70 60,40 100,45 C140,50 160,20 200,25 C240,30 260,10 300,15 L300,110 L0,110 Z" />

                        <!-- garis tren utama -->
                        <path class="chart-line"
                              d="M0,80 C40,70 60,40 100,45 C140,50 160,20 200,25 C240,30 260,10 300,15" />

                        <!-- titik-titik penanda -->
                        <circle class="chart-dot" cx="0" cy="80" r="3.5" fill="#60a5fa" />
                        <circle class="chart-dot" cx="100" cy="45" r="3.5" fill="#60a5fa" />
                        <circle class="chart-dot" cx="200" cy="25" r="3.5" fill="#60a5fa" />
                        <circle class="chart-dot chart-pulse" cx="300" cy="15" r="3.5" />
                        <circle class="chart-dot" cx="300" cy="15" r="3.5" fill="#93c5fd" />

                        <defs>
                            <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#60a5fa" stop-opacity="0.28" />
                                <stop offset="100%" stop-color="#60a5fa" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </div>

            <div class="brand-foot">FIN-{{ date('Y') }} · AKSES INTERNAL</div>
        </aside>

        <!-- PANEL KANAN: FORM LOGIN -->
        <main class="form-panel">
            <div class="form-panel-inner">
                <div class="section-title">Secure Access</div>
                <h1 class="greeting">Selamat datang kembali</h1>
                <p class="lead">Masuk untuk membuka dashboard Finance</p>

                @if($errors->any())
                    <div class="error-message">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Kata sandi</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat saya pada perangkat ini</label>
                    </div>

                    <button type="submit" class="btn-login">Masuk ke dashboard</button>
                </form>

                
            </div>
        </main>

    </div>
</body>
</html>