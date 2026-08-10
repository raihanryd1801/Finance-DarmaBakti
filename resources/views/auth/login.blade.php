<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Finance</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f3f4f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.875rem; }
        .form-control { width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; box-sizing: border-box; }
        .btn { width: 100%; padding: 0.75rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
        .error { color: #ef4444; font-size: 0.8rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 style="margin-top: 0; margin-bottom: 1.5rem; text-align: center;">Login Finance</h2>
        
        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn">Masuk Sistem</button>
        </form>
    </div>
</body>
</html>