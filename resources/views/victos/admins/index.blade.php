<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Victos Admins</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#f1f5f9;color:#0f172a;font:15px/1.5 Inter,system-ui,Segoe UI,Arial,sans-serif}
        main{max-width:1100px;margin:auto;padding:26px 16px}.head,.panel{background:#fff;border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 12px 30px #0f172a0a}
        .head{padding:18px 22px;display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px}.head h1{font-size:22px;margin:0}
        .panel{padding:18px}.actions{display:flex;gap:8px;flex-wrap:wrap}.btn{display:inline-block;padding:10px 14px;border-radius:9px;text-decoration:none;border:0;background:#4f46e5;color:#fff;font-weight:650;cursor:pointer}
        .secondary{background:#eef2ff;color:#3730a3}.flash{background:#dcfce7;color:#166534;padding:12px;border-radius:10px;margin-bottom:14px}
        .table-wrap{overflow:auto}table{width:100%;border-collapse:collapse;min-width:720px}th,td{text-align:left;padding:12px;border-bottom:1px solid #e2e8f0;vertical-align:top}
        th{font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.04em}
        @media(max-width:600px){.head{align-items:flex-start;flex-direction:column}main{padding:14px 10px}.panel{padding:12px}}
    </style>
</head>
<body><main>
    <header class="head"><h1>Tenant admins</h1><div class="actions">
        <a class="btn" href="{{ route('victos.admins.create') }}">Create admin</a>
        <a class="btn secondary" href="{{ route('victos.dashboard') }}">Dashboard</a>
        <a class="btn secondary" href="{{ route('victos.profile.show') }}">Profile</a>
    </div></header>
    @if (session('success')) <div class="flash">{{ session('success') }}</div> @endif
    <section class="panel"><div class="table-wrap"><table>
        <thead><tr><th>Admin</th><th>Email</th><th>Role</th><th>Status records</th><th>Allowed domains</th></tr></thead>
        <tbody>@forelse ($admins as $admin)
            <tr><td>{{ $admin->name }}</td><td>{{ $admin->email }}</td><td>Admin</td><td>{{ $admin->status_records_count }}</td><td>{{ $admin->allowed_domains_for_display ?: 'Not configured' }}</td></tr>
        @empty <tr><td colspan="5">No additional admins yet.</td></tr> @endforelse</tbody>
    </table></div>{{ $admins->links() }}</section>
</main></body></html>
