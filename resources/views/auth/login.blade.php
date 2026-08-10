<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Finance</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem;
        }
        .login-wrapper {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }
        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .brand h1 span {
            color: #2563eb;
        }
        .brand .subtitle {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.25rem;
            font-weight: 400;
        }
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 1.5rem 0;
        }
        .section-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        .greeting {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
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
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #f8fafc;
        }
        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background: #ffffff;
        }
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .form-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
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
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background: #1d4ed8;
        }
        .demo-hint {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
            font-size: 0.8rem;
            color: #64748b;
            text-align: center;
        }
        .demo-hint strong {
            color: #1e293b;
            font-weight: 600;
        }
        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            border-left: 4px solid #dc2626;
        }
        @media (max-width: 480px) {
            .login-wrapper { padding: 1.5rem; }
            .brand h1 { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Branding -->
        <div class="brand">
            <h1>FINANCE <span>APP</span></h1>
            <div class="subtitle">Proactive threat detection for corporate networks.</div>
        </div>

        <div class="divider"></div>

        <!-- Secure Access -->
        <div class="section-title">Secure Access</div>
        <div class="greeting">Selamat datang kembali</div>
        <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem;">Masuk untuk membuka dashboard monitoring.</p>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="admin@finance.com" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Kata sandi</label>
                <input type="password" id="password" name="password" class="form-control" value="password" required>
            </div>

            <div class="form-check">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ingat saya pada perangkat ini</label>
            </div>

            <button type="submit" class="btn-login">Masuk ke dashboard</button>
        </form>

    </div>
</body>
</html>