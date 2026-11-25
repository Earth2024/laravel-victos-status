<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Victos Profile</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#f1f5f9;color:#0f172a;font:15px/1.5 Inter,system-ui,Segoe UI,Arial,sans-serif}
        .shell{max-width:980px;margin:auto;padding:28px 18px}.top,.panel{background:#fff;border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 12px 30px #0f172a0a}
        .top{padding:18px 22px;display:flex;justify-content:space-between;align-items:center;gap:14px;margin-bottom:18px}.top h1{font-size:22px;margin:0}
        .nav,.actions{display:flex;gap:9px;flex-wrap:wrap;align-items:center}.nav a,.button{border:0;border-radius:9px;padding:10px 14px;text-decoration:none;font-weight:650;cursor:pointer;font-size:14px}
        .nav a,.secondary{background:#eef2ff;color:#3730a3}.primary{background:#4f46e5;color:#fff}.panel{padding:22px;margin:16px 0}.panel h2{margin:0 0 6px;font-size:19px}
        .hint{color:#64748b;font-size:13px;margin:0 0 16px}.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px}
        label{display:block;font-size:13px;font-weight:650;margin-bottom:6px}input,textarea{width:100%;border:1px solid #cbd5e1;border-radius:9px;padding:11px 12px;font:inherit;background:#fff;color:#0f172a}
        textarea{min-height:110px;resize:vertical}pre{white-space:pre-wrap;overflow-wrap:anywhere;background:#0f172a;color:#dbeafe;border-radius:10px;padding:14px;font-size:12px}
        .flash,.error{padding:12px 14px;border-radius:10px;margin:12px 0}.flash{background:#dcfce7;color:#166534}.error{background:#fee2e2;color:#991b1b}
        @media(max-width:640px){.shell{padding:16px 12px}.top{align-items:flex-start;flex-direction:column}.grid{grid-template-columns:1fr}.panel{padding:17px}}
    </style>
</head>
<body>
<main class="shell">
    <header class="top">
        <h1>Profile &amp; widget setup</h1>
        <nav class="nav"><a href="{{ route('victos.dashboard') }}">Dashboard</a>
            @if (($user->role ?? null) === 'super_admin') <a href="{{ route('victos.admins.index') }}">Admins</a> @endif
        </nav>
    </header>
    @if (session('success')) <div class="flash">{{ session('success') }}</div> @endif
    @if ($errors->any()) <div class="error">{{ $errors->first() }}</div> @endif

    <section class="panel">
        <h2>Account and allowed websites</h2>
        <p class="hint">Only these website origins can use this tenant’s public status widget. Enter one hostname per line; include a port for local development.</p>
        <form method="POST" action="{{ route('victos.profile.update') }}">
            @csrf @method('PUT')
            <div class="grid">
                <div><label for="name">Name</label><input id="name" name="name" value="{{ old('name', $user->name) }}" required></div>
                <div><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
                <div style="grid-column:1/-1"><label for="allowed_domains_text">Allowed domains</label><textarea id="allowed_domains_text" name="allowed_domains_text" placeholder="example.com&#10;www.example.com&#10;localhost:5173">{{ old('allowed_domains_text', $allowedDomainsText) }}</textarea></div>
            </div>
            <p class="hint">Your public tenant key: <code>{{ $user->public_tenant_key }}</code></p>
            <button class="button primary" type="submit">Save profile</button>
        </form>
    </section>

    <section class="panel">
        <h2>Use the widget on any page</h2>
        <p class="hint">Add a mount element where the button should appear, include the standalone JavaScript asset, then initialize it. Works with plain HTML, Blade, Vue and React. SPA integrations should call <code>destroy()</code> when the component unmounts.</p>
        <pre>&lt;div data-victos-check-status
     data-tenant-key="{{ $user->public_tenant_key }}"
     data-endpoint="{{ $endpoint }}"&gt;&lt;/div&gt;
&lt;script src="{{ $assetUrl }}" defer&gt;&lt;/script&gt;
&lt;script&gt;window.addEventListener('DOMContentLoaded', function () {
  window.VictosCheckStatus.init();
});&lt;/script&gt;</pre>
    </section>

    <section class="panel">
        <h2>Change password</h2>
        <p class="hint">Enter your current password and confirm a new password.</p>
        <form method="POST" action="{{ route('victos.profile.password') }}">
            @csrf @method('PUT')
            <div class="grid">
                <div><label for="current_password">Current password</label><input id="current_password" type="password" name="current_password" autocomplete="current-password" required></div>
                <div><label for="password">New password</label><input id="password" type="password" name="password" autocomplete="new-password" required></div>
                <div><label for="password_confirmation">Confirm new password</label><input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required></div>
            </div>
            <p style="margin-bottom:0"><button class="button primary" type="submit">Change password</button></p>
        </form>
    </section>
</main>
</body>
</html>
