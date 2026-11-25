<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f1f5f9">
    <title>Victos Dashboard</title>
    <style>
        *{box-sizing:border-box}
        body{margin:0;background:#f7f8fc;color:#172033;font:14px/1.55 Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif}
        a{color:inherit}
        .app-shell{min-height:100vh;display:grid;grid-template-columns:252px minmax(0,1fr)}
        .sidebar{position:sticky;top:0;height:100vh;padding:24px 16px 18px;background:#111827;color:#cbd5e1;display:flex;flex-direction:column}
        .brand{display:flex;align-items:center;gap:11px;padding:2px 9px 28px;color:#fff;text-decoration:none;font-size:18px;font-weight:750;letter-spacing:-.03em}
        .brand-mark{width:36px;height:36px;display:grid;place-items:center;border-radius:11px;background:linear-gradient(145deg,#818cf8,#4f46e5);color:white;font-size:17px}
        .brand small{display:block;margin-top:1px;color:#94a3b8;font-size:9px;font-weight:700;letter-spacing:.14em}
        .side-label{padding:0 11px;margin:0 0 9px;color:#64748b;font-size:10px;font-weight:750;letter-spacing:.13em;text-transform:uppercase}
        .side-nav{display:grid;gap:5px}
        .side-nav a{display:flex;align-items:center;gap:11px;min-height:42px;padding:9px 11px;border-radius:9px;color:#aab5c7;text-decoration:none;font-size:13px;font-weight:600;transition:background .15s,color .15s}
        .side-nav a:hover,.side-nav a.active{background:#252f42;color:#fff}
        .side-nav a.active{box-shadow:inset 3px 0 #818cf8}
        .side-nav svg{width:17px;height:17px;flex:0 0 17px;opacity:.85}
        .sidebar-bottom{margin-top:auto;border-top:1px solid #273244;padding:15px 3px 0}
        .user-card{display:flex;align-items:center;gap:10px;padding:7px 7px 13px}
        .user-avatar{width:34px;height:34px;border-radius:50%;display:grid;place-items:center;background:#3730a3;color:#e0e7ff;font-weight:750}
        .user-meta{min-width:0}.user-meta strong,.user-meta span{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .user-meta strong{color:#f8fafc;font-size:12px}.user-meta span{color:#94a3b8;font-size:11px}
        .main-area{min-width:0;padding:0 clamp(18px,3vw,42px) 42px}
        .topbar{height:66px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e7eaf1;color:#64748b;font-size:12px}
        .crumbs{display:flex;align-items:center;gap:9px}.crumbs strong{color:#334155;font-weight:650}
        .top-user{display:flex;align-items:center;gap:9px;color:#334155;font-weight:600}
        .top-avatar{width:30px;height:30px;border-radius:50%;display:grid;place-items:center;background:#e0e7ff;color:#4338ca;font-weight:750}
        .content{max-width:1500px;margin:0 auto}
        .page-heading{display:flex;justify-content:space-between;align-items:flex-end;gap:18px;padding:29px 0 22px}
        .eyebrow{margin:0 0 5px;color:#6366f1;font-size:10px;font-weight:800;letter-spacing:.14em}
        h1{margin:0;color:#111827;font-size:27px;line-height:1.2;letter-spacing:-.045em}
        .page-subtitle{margin:7px 0 0;color:#718096;font-size:13px}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 14px;border:1px solid transparent;border-radius:9px;cursor:pointer;text-decoration:none;font-family:inherit;font-size:13px;font-weight:650;line-height:1.2;white-space:nowrap;transition:transform .15s,background .15s,box-shadow .15s}
        .btn:hover{transform:translateY(-1px)}
        .btn-primary{background:#4f46e5;color:#fff;box-shadow:0 5px 12px #4f46e52b}
        .btn-primary:hover{background:#4338ca}
        .btn-secondary{background:#fff;border-color:#e2e8f0;color:#344054}
        .btn-secondary:hover{background:#f8fafc}
        .btn-danger{background:#fff1f2;border-color:#ffe4e6;color:#be123c}
        .logout-btn{width:100%;justify-content:flex-start;background:transparent;color:#aab5c7;padding:9px 11px}
        .logout-btn:hover{background:#252f42;color:#fff}
        .stats-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:20px}
        .stat-card,.panel{background:#fff;border:1px solid #e8ebf2;border-radius:13px;box-shadow:0 3px 12px rgba(15,23,42,.025)}
        .stat-card{padding:17px 18px;min-width:0}
        .stat-head{display:flex;justify-content:space-between;align-items:center;gap:8px;color:#697586;font-size:12px;font-weight:600}
        .stat-icon{width:32px;height:32px;display:grid;place-items:center;border-radius:9px;background:#eef2ff;color:#4f46e5}
        .stat-icon svg{width:17px;height:17px}
        .stat-value{margin-top:13px;color:#111827;font-size:25px;line-height:1.15;font-weight:750;letter-spacing:-.04em;overflow-wrap:anywhere}
        .stat-foot{margin-top:5px;color:#98a2b3;font-size:11px}
        .stat-progress{height:5px;margin-top:12px;border-radius:9px;background:#eef0f5;overflow:hidden}
        .stat-progress span{display:block;height:100%;border-radius:inherit;background:linear-gradient(90deg,#6366f1,#8b5cf6)}
        .panel{padding:22px;margin-bottom:18px;scroll-margin-top:18px}
        .panel-heading{display:flex;justify-content:space-between;align-items:flex-start;gap:15px;margin-bottom:18px}
        h2{margin:0;color:#182230;font-size:17px;letter-spacing:-.025em}
        .muted{margin:4px 0 0;color:#7b8798;font-size:12px}
        .panel h3{margin:22px 0 10px;color:#344054!important;font-size:13px;font-weight:700}
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,220px),1fr));gap:14px}
        label{display:block;margin-bottom:6px;color:#475467;font-size:12px;font-weight:650}
        input{width:100%;padding:10px 11px;border:1px solid #d8dee8;border-radius:8px;background:#fff;color:#172033;font-family:inherit;font-size:13px;transition:border-color .15s,box-shadow .15s}
        input:focus{outline:none;border-color:#818cf8;box-shadow:0 0 0 3px #eef2ff}
        .flash,.error{margin:0 0 14px;padding:12px 14px;border-radius:9px;font-size:13px}
        .flash{background:#ecfdf3;border:1px solid #abefc6;color:#067647}
        .error{background:#fff1f2;border:1px solid #fecdd3;color:#be123c}
        .actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
        form.inline{display:inline;margin:0}
        .table-wrap{overflow:auto;border:1px solid #e8ebf2;border-radius:10px}
        table{width:100%;border-collapse:collapse;min-width:1050px}
        th,td{padding:12px 13px;border-bottom:1px solid #edf0f5;text-align:left;vertical-align:middle;font-size:12px}
        th{background:#fafbfc;color:#697586;font-size:10px;font-weight:750;text-transform:uppercase;letter-spacing:.06em}
        tbody tr:last-child td{border-bottom:0}
        tbody tr:hover{background:#fafbff}
        .pagination{display:flex;gap:5px;flex-wrap:wrap}
        @media(max-width:1100px){.app-shell{grid-template-columns:220px minmax(0,1fr)}.stats-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:760px){
            .app-shell{display:block}
            .sidebar{position:relative;width:100%;height:auto;padding:12px 14px}
            .brand{padding:2px 4px 12px}
            .side-label,.sidebar-bottom{display:none}
            .side-nav{display:flex;overflow:auto;gap:5px;padding-bottom:2px}
            .side-nav a{flex:0 0 auto;min-height:38px;padding:8px 10px}
            .side-nav a.active{box-shadow:inset 0 -2px #818cf8}
            .main-area{padding:0 16px 28px}
            .topbar{height:52px}
            .page-heading{align-items:flex-start;flex-direction:column;padding:22px 0 17px}
            .page-heading .actions{width:100%}
            .page-heading .btn{flex:1}
            .panel{padding:17px}
        }
        @media(max-width:440px){
            .stats-grid{grid-template-columns:1fr 1fr;gap:9px}
            .stat-card{padding:13px}
            .stat-value{font-size:21px}
            .top-user span{display:none}
            .main-area{padding-left:11px;padding-right:11px}
            .panel{padding:14px}
            .grid{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('victos.dashboard') }}">
            <span class="brand-mark">V</span><span>Victos<small>STATUS PORTAL</small></span>
        </a>
        <p class="side-label">Workspace</p>
        <nav class="side-nav" aria-label="Admin navigation">
            <a class="active" href="#overview"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="7" height="8" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="14" y="3" width="7" height="5" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="14" y="11" width="7" height="10" rx="1.5" stroke="currentColor" stroke-width="1.7"/><rect x="3" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.7"/></svg>Overview</a>
            <a href="#records"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 4h10M7 8h10M7 12h10M7 16h6M5 2h14a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>Status records</a>
            <a href="#settings"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v2m0 14v2M3 12h2m14 0h2M5.64 5.64l1.42 1.42m9.88 9.88 1.42 1.42m0-12.72-1.42 1.42m-9.88 9.88-1.42 1.42" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.7"/></svg>Card settings</a>
            <a href="{{ route('victos.profile.show') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.7"/><path d="M4 21a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>Profile &amp; widget</a>
            @if (($user->role ?? null) === 'super_admin')
                <a href="{{ route('victos.admins.index') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m16-13a4 4 0 0 1 0 8m4 5v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.7"/></svg>Manage admins</a>
            @endif
        </nav>
        <div class="sidebar-bottom">
            <div class="user-card"><span class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><div class="user-meta"><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span></div></div>
            <form method="POST" action="{{ route('victos.logout') }}">@csrf<button class="btn logout-btn" type="submit">↪&nbsp; Sign out</button></form>
        </div>
    </aside>

    <main class="main-area">
        <div class="topbar">
            <div class="crumbs"><span>Workspace</span><span aria-hidden="true">/</span><strong>Overview</strong></div>
            <div class="top-user"><span class="top-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><span>{{ $user->name }}</span></div>
        </div>
        <div class="content">
    @if (session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <section class="page-heading" id="overview">
        <div><p class="eyebrow">TENANT OVERVIEW</p><h1>Dashboard</h1><p class="page-subtitle">A clear view of your refund records and status card configuration.</p></div>
        <div class="actions"><a class="btn btn-primary" href="{{ route('victos.records.create') }}">＋&nbsp; Add status record</a></div>
    </section>

    <section class="stats-grid" aria-label="Tenant summary">
        <article class="stat-card"><div class="stat-head"><span>Total records</span><span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 4h10M7 8h10M7 12h10M7 16h6M5 2h14a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span></div><div class="stat-value">{{ number_format($dashboardStats['record_count']) }}</div><div class="stat-foot">Address status records</div></article>
        <article class="stat-card"><div class="stat-head"><span>Total eligible</span><span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2v20m5-15H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span></div><div class="stat-value">${{ number_format($dashboardStats['eligible_total'], 2) }}</div><div class="stat-foot">Across your records</div></article>
        <article class="stat-card"><div class="stat-head"><span>Eligible paid</span><span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.7"/></svg></span></div><div class="stat-value">${{ number_format($dashboardStats['eligible_paid'], 2) }}</div><div class="stat-foot">Paid toward eligible totals</div></article>
        <article class="stat-card"><div class="stat-head"><span>Overall refunded</span><span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 17 9 12l4 3 7-8M15 7h5v5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span></div><div class="stat-value">{{ number_format($dashboardStats['refunded_percent'], 1) }}%</div><div class="stat-progress"><span style="width:{{ $dashboardStats['refunded_percent'] }}%"></span></div><div class="stat-foot">Weighted across eligible amounts</div></article>
    </section>

    <section class="panel" id="settings">
        <div class="panel-heading"><div><h2>Your card settings</h2><p class="muted">Set default content and appearance. Individual records can override these values.</p></div></div>
        <form method="POST" action="{{ route('victos.settings.update') }}">
            @csrf
            <h3>Card text &amp; colors</h3>
            <div class="grid">
                <div><label>Card title</label><input name="card_title" value="{{ old('card_title', $settings->card_title) }}" required></div>
                <div><label>Eligibility badge text</label><input name="eligibility_badge_text" value="{{ old('eligibility_badge_text', $settings->eligibility_badge_text) }}" required></div>
                <div><label>Connected wallet label</label><input name="connected_wallet_label" value="{{ old('connected_wallet_label', $settings->connected_wallet_label) }}" required></div>
                <div><label>Total refund label</label><input name="total_refund_label" value="{{ old('total_refund_label', $settings->total_refund_label) }}" required></div>
                <div><label>Signature percentage label</label><input name="signature_percentage_label" value="{{ old('signature_percentage_label', $settings->signature_percentage_label) }}" required></div>
                <div><label>Refund status label</label><input name="refund_status_label" value="{{ old('refund_status_label', $settings->refund_status_label) }}" required></div>
                <div><label>Signature progress label</label><input name="signature_progress_label" value="{{ old('signature_progress_label', $settings->signature_progress_label) }}" required></div>
                <div><label>Card background (from)</label><input name="card_bg_from" value="{{ old('card_bg_from', $settings->card_bg_from) }}" required></div>
                <div><label>Card background (to)</label><input name="card_bg_to" value="{{ old('card_bg_to', $settings->card_bg_to) }}" required></div>
                <div><label>Card border color</label><input name="card_border_color" value="{{ old('card_border_color', $settings->card_border_color) }}" required></div>
                <div><label>Badge background color</label><input name="badge_bg_color" value="{{ old('badge_bg_color', $settings->badge_bg_color) }}" required></div>
                <div><label>Badge text color</label><input name="badge_text_color" value="{{ old('badge_text_color', $settings->badge_text_color) }}" required></div>
                <div><label>Accent color</label><input name="accent_color" value="{{ old('accent_color', $settings->accent_color) }}" required></div>
                <div><label>Progress bar color</label><input name="progress_bar_color" value="{{ old('progress_bar_color', $settings->progress_bar_color) }}" required></div>
                <div><label>Amount color</label><input name="amount_color" value="{{ old('amount_color', $settings->amount_color) }}" required></div>
            </div>

            <h3>Check Status button &amp; modal</h3>
            <p class="muted" style="margin-top:0;">Customize the public “Check Status” button and the address-check modal texts/styles shown before a card is loaded.</p>
            <div class="grid">
                <div><label>Check Status button text</label><input name="check_status_button_text" value="{{ old('check_status_button_text', $settings->check_status_button_text ?? 'Check Status') }}" required></div>
                <div><label>Check Status button text color</label><input name="check_status_button_text_color" value="{{ old('check_status_button_text_color', $settings->check_status_button_text_color ?? '#ffffff') }}" required></div>
                <div><label>Check Status button background color</label><input name="check_status_button_bg_color" value="{{ old('check_status_button_bg_color', $settings->check_status_button_bg_color ?? '#2563eb') }}" required></div>
                <div><label>Check Status button font size</label><input name="check_status_button_font_size" value="{{ old('check_status_button_font_size', $settings->check_status_button_font_size ?? '14px') }}" required placeholder="e.g. 14px"></div>
                <div><label>Modal header text</label><input name="modal_header_text" value="{{ old('modal_header_text', $settings->modal_header_text ?? 'Check Status') }}" required></div>
                <div><label>Modal address label text</label><input name="modal_address_label_text" value="{{ old('modal_address_label_text', $settings->modal_address_label_text ?? 'Enter your address') }}" required></div>
                <div><label>Modal submit button text</label><input name="modal_submit_text" value="{{ old('modal_submit_text', $settings->modal_submit_text ?? 'Check Status') }}" required></div>
            </div>

            <h3>Default card font sizes</h3>
            <p class="muted" style="margin-top:0;">Set default font sizes for each part of the card (e.g. 16px, 1rem). Individual records can override these.</p>
            <div class="grid">
                <div><label>Card title font size</label><input name="card_title_font_size" value="{{ old('card_title_font_size', $settings->card_title_font_size ?? '16px') }}" required></div>
                <div><label>Badge font size</label><input name="badge_font_size" value="{{ old('badge_font_size', $settings->badge_font_size ?? '12px') }}" required></div>
                <div><label>Section label font size</label><input name="section_label_font_size" value="{{ old('section_label_font_size', $settings->section_label_font_size ?? '10px') }}" required></div>
                <div><label>Wallet value font size</label><input name="wallet_value_font_size" value="{{ old('wallet_value_font_size', $settings->wallet_value_font_size ?? '14px') }}" required></div>
                <div><label>Total refund amount font size</label><input name="amount_font_size" value="{{ old('amount_font_size', $settings->amount_font_size ?? '28px') }}" required></div>
                <div><label>Stat label font size</label><input name="stat_label_font_size" value="{{ old('stat_label_font_size', $settings->stat_label_font_size ?? '10px') }}" required></div>
                <div><label>Stat value font size</label><input name="stat_value_font_size" value="{{ old('stat_value_font_size', $settings->stat_value_font_size ?? '24px') }}" required></div>
                <div><label>Progress label font size</label><input name="progress_label_font_size" value="{{ old('progress_label_font_size', $settings->progress_label_font_size ?? '14px') }}" required></div>
                <div><label>Progress percent font size</label><input name="progress_percent_font_size" value="{{ old('progress_percent_font_size', $settings->progress_percent_font_size ?? '18px') }}" required></div>
                <div><label>Remaining text font size</label><input name="remaining_font_size" value="{{ old('remaining_font_size', $settings->remaining_font_size ?? '13px') }}" required></div>
            </div>
            <div style="margin-top:14px;"><button class="btn btn-primary" type="submit">Save Settings</button></div>
        </form>
    </section>

    <section class="panel" id="records">
        <div class="panel-heading"><div><h2>Status records</h2>
        <p class="muted">Visitors on your allowlisted website can check these addresses. Records are private to your tenant in the admin area.</p>
        </div><a class="btn btn-secondary" href="{{ route('victos.records.create') }}">＋&nbsp; Add record</a></div>
        @if ($records->isEmpty())
            <p class="muted">No records yet.</p>
        @else
            <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Address</th>
                        <th>Wallet display</th>
                        <th>Refund (display)</th>
                        <th>Eligible / Paid</th>
                        <th>Refunded %</th>
                        <th>Remaining %</th>
                        <th>Refund status</th>
                        <th>Progress</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($records as $record)
                    @php
                        $pct = $record->refundedPercentage();
                        $rem = $record->remainingPercentage();
                    @endphp
                    <tr>
                        <td>{{ $record->address }}</td>
                        <td>{{ $record->wallet_display }}</td>
                        <td>{{ $record->total_refund_amount ?: $record->formattedTotalRefundAmount() }}</td>
                        <td>${{ number_format((float) ($record->total_eligible_amount ?? 0), 2) }} / ${{ number_format((float) ($record->eligible_amount_paid ?? 0), 2) }}</td>
                        <td>{{ $record->formatPercent($pct) }}</td>
                        <td>{{ $record->formatPercent($rem) }}</td>
                        <td>{{ $record->refund_status_value }}</td>
                        <td>{{ $record->signature_progress_status }} ({{ $record->formatPercent($pct) }})</td>
                        <td>{{ $record->updated_at }}</td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-secondary" href="{{ route('victos.records.edit', $record) }}">Edit</a>
                                <form class="inline" method="POST" action="{{ route('victos.records.destroy', $record) }}" onsubmit="return confirm('Delete this record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            <div style="margin-top:14px">{{ $records->links() }}</div>
        @endif
    </section>
        </div>
    </main>
</div>
</body>
</html>
