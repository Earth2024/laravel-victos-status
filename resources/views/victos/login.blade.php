<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Victos Login</title>
    <style>
        *{box-sizing:border-box}body{margin:0;padding:18px;font:15px/1.5 Inter,system-ui,-apple-system,Segoe UI,Arial,sans-serif;background:#f1f5f9;color:#0f172a;display:grid;place-items:center;min-height:100vh}
        .card{width:min(100%,430px);background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:clamp(20px,5vw,30px);box-shadow:0 20px 60px rgba(15,23,42,.1)}
        h1{margin:0 0 18px;font-size:25px;letter-spacing:-.03em}.label{display:block;margin:13px 0 6px;font-size:14px;font-weight:650;color:#334155}
        input[type="email"],input[type="password"]{width:100%;padding:11px 12px;border-radius:9px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font:inherit}
        .row{margin-top:12px;display:flex;align-items:center;gap:8px;font-size:14px;color:#475569}.row label{margin:0}
        button{margin-top:18px;width:100%;padding:12px;border:0;border-radius:9px;background:#4f46e5;color:#fff;font-size:15px;font-weight:700;cursor:pointer}
        .error{margin:0 0 12px;padding:10px 12px;border-radius:9px;background:#fee2e2;color:#991b1b;border:1px solid #fecaca;font-size:14px}
    </style>
</head>
<body>
    <div class="card">
        <h1>Victos Login</h1>
        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('victos.login.submit') }}">
            @csrf
            <label class="label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

            <label class="label" for="password">Password</label>
            <input id="password" type="password" name="password" required>

            <div class="row">
                <input id="remember" type="checkbox" name="remember" value="1">
                <label for="remember" style="margin:0;">Remember me</label>
            </div>

            <button type="submit">Log in</button>
        </form>
    </div>
</body>
</html>
