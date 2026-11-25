<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Victos Admin</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#f1f5f9;color:#0f172a;font:15px/1.5 Inter,system-ui,Segoe UI,Arial,sans-serif}
        main{max-width:760px;margin:auto;padding:26px 16px}.panel{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:22px;box-shadow:0 12px 30px #0f172a0a}
        h1{font-size:22px;margin:0 0 6px}.hint{color:#64748b;font-size:13px;margin:0 0 16px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
        label{display:block;font-weight:650;font-size:13px;margin-bottom:6px}input,textarea{width:100%;padding:11px 12px;border:1px solid #cbd5e1;border-radius:9px;font:inherit}textarea{min-height:100px}
        .btn{display:inline-block;padding:10px 14px;border-radius:9px;text-decoration:none;border:0;background:#4f46e5;color:#fff;font-weight:650;cursor:pointer}.secondary{background:#eef2ff;color:#3730a3}
        .error{background:#fee2e2;color:#991b1b;padding:12px;border-radius:10px;margin:12px 0}.actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:16px}
        @media(max-width:600px){main{padding:14px 10px}.panel{padding:17px}.grid{grid-template-columns:1fr}}
    </style>
</head>
<body><main><section class="panel">
    <h1>Create admin account</h1>
    <p class="hint">New admins can manage their own settings and records but cannot create or manage other admins. Use a unique password with at least 12 characters.</p>
    @if ($errors->any()) <div class="error">{{ $errors->first() }}</div> @endif
    <form method="POST" action="{{ route('victos.admins.store') }}">@csrf
        <div class="grid">
            <div><label for="name">Name</label><input id="name" name="name" value="{{ old('name') }}" required></div>
            <div><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required></div>
            <div><label for="password">Temporary password</label><input id="password" type="password" name="password" minlength="12" required></div>
            <div><label for="password_confirmation">Confirm password</label><input id="password_confirmation" type="password" name="password_confirmation" minlength="12" required></div>
            <div style="grid-column:1/-1"><label for="allowed_domains_text">Allowed domains (one per line)</label><textarea id="allowed_domains_text" name="allowed_domains_text" placeholder="example.com">{{ old('allowed_domains_text') }}</textarea></div>
        </div>
        <div class="actions"><button class="btn" type="submit">Create admin</button><a class="btn secondary" href="{{ route('victos.admins.index') }}">Cancel</a></div>
    </form>
</section></main></body></html>
