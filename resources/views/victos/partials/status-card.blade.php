@php
    $percent = isset($d['progress_percent_number']) ? (float) $d['progress_percent_number'] : 0.0;
    if (!isset($d['progress_percent_number']) && preg_match('/(\d+(?:\.\d+)?)/', (string) ($d['signature_progress_percent'] ?? '0'), $m)) {
        $percent = (float) $m[1];
    }
    $percent = max(0, min(100, $percent));
    $bgFrom = $s['card_bg_from'] ?? '#071a3d';
    $bgTo = $s['card_bg_to'] ?? '#06142e';
    $border = $s['card_border_color'] ?? 'rgba(99,102,241,0.35)';
    $badgeBg = $s['badge_bg_color'] ?? '#22c55e';
    $badgeText = $s['badge_text_color'] ?? '#ffffff';
    $accent = $s['accent_color'] ?? '#7c3aed';
    $progress = $s['progress_bar_color'] ?? '#22c55e';
    $amountColor = $s['amount_color'] ?? '#ffffff';
    $titleFs = $s['card_title_font_size'] ?? '16px';
    $badgeFs = $s['badge_font_size'] ?? '12px';
    $sectionLabelFs = $s['section_label_font_size'] ?? '10px';
    $walletFs = $s['wallet_value_font_size'] ?? '14px';
    $amountFs = $s['amount_font_size'] ?? '28px';
    $statLabelFs = $s['stat_label_font_size'] ?? '10px';
    $statValueFs = $s['stat_value_font_size'] ?? '24px';
    $progressLabelFs = $s['progress_label_font_size'] ?? '14px';
    $progressPctFs = $s['progress_percent_font_size'] ?? '18px';
    $remainingFs = $s['remaining_font_size'] ?? '13px';
@endphp
<div class="vs-card" style="--vs-bg-from: {{ $bgFrom }}; --vs-bg-to: {{ $bgTo }}; --vs-border: {{ $border }}; --vs-badge-bg: {{ $badgeBg }}; --vs-badge-text: {{ $badgeText }}; --vs-accent: {{ $accent }}; --vs-progress: {{ $progress }}; --vs-amount: {{ $amountColor }}; --vs-title-fs: {{ $titleFs }}; --vs-badge-fs: {{ $badgeFs }}; --vs-section-label-fs: {{ $sectionLabelFs }}; --vs-wallet-fs: {{ $walletFs }}; --vs-amount-fs: {{ $amountFs }}; --vs-stat-label-fs: {{ $statLabelFs }}; --vs-stat-value-fs: {{ $statValueFs }}; --vs-progress-label-fs: {{ $progressLabelFs }}; --vs-progress-pct-fs: {{ $progressPctFs }}; --vs-remaining-fs: {{ $remainingFs }};">
    <style>
        .vs-card,.vs-card *{box-sizing:border-box}
        .vs-card{font-family:Inter,Segoe UI,Arial,sans-serif;color:#e8eefc;background:radial-gradient(120% 120% at 100% 0%,rgba(76,29,149,.35) 0%,rgba(7,26,61,0) 40%),linear-gradient(180deg,var(--vs-bg-from),var(--vs-bg-to));border:1px solid var(--vs-border);border-radius:20px;padding:14px;box-shadow:0 20px 50px rgba(2,8,23,.55),inset 0 1px 0 rgba(255,255,255,.04);width:100%;max-width:100%;margin:0 auto;overflow:hidden}
        .vs-row{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;flex-wrap:wrap}
        .vs-title-wrap{display:flex;align-items:center;gap:8px;min-width:0;flex:1 1 140px}
        .vs-title-icon{width:30px;height:30px;border-radius:10px;background:linear-gradient(180deg,#8b5cf6,#6d28d9);display:flex;align-items:center;justify-content:center;box-shadow:inset 0 1px 0 rgba(255,255,255,.25);flex:0 0 auto}
        .vs-title{font-size:var(--vs-title-fs,16px);font-weight:700;color:#f8fbff;letter-spacing:.1px;line-height:1.2;word-break:break-word}
        .vs-badge{display:inline-flex;align-items:center;gap:6px;background:linear-gradient(180deg,color-mix(in srgb,var(--vs-badge-bg),#fff 12%),var(--vs-badge-bg));color:var(--vs-badge-text);padding:6px 10px;border-radius:999px;font-weight:700;font-size:var(--vs-badge-fs,12px);box-shadow:0 6px 18px rgba(34,197,94,.25);white-space:nowrap;flex:0 0 auto}
        .vs-wallet-section{margin-top:12px;display:flex;gap:10px;align-items:flex-start;justify-content:space-between}
        .vs-wallet-main{flex:1;min-width:0}
        .vs-label{font-size:var(--vs-section-label-fs,10px);font-weight:700;letter-spacing:.7px;color:#9fb0d6;text-transform:uppercase;margin-bottom:8px;line-height:1.3}
        .vs-wallet-box{display:flex;align-items:center;justify-content:space-between;gap:8px;background:rgba(7,18,40,.7);border:1px solid rgba(148,163,184,.18);border-radius:12px;padding:10px 12px;color:#eef4ff;font-size:var(--vs-wallet-fs,14px);font-weight:600;min-width:0}
        .vs-wallet-box span:first-child{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;min-width:0}
        .vs-copy{width:24px;height:24px;border-radius:7px;border:1px solid rgba(148,163,184,.2);display:flex;align-items:center;justify-content:center;color:#cbd5e1;flex:0 0 auto}
        .vs-illus{display:none}
        .vs-refund{margin-top:12px}
        .vs-refund-head{display:flex;align-items:center;gap:8px;margin-bottom:6px}
        .vs-dollar{width:18px;height:18px;border-radius:999px;background:rgba(34,197,94,.18);color:#4ade80;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:11px;flex:0 0 auto}
        .vs-amount{font-size:var(--vs-amount-fs,28px);line-height:1.1;font-weight:800;color:var(--vs-amount);letter-spacing:.2px;word-break:break-word}
        .vs-stats{margin-top:12px;display:grid;grid-template-columns:1fr;gap:10px}
        .vs-stat{background:rgba(8,20,44,.55);border:1px solid rgba(148,163,184,.16);border-radius:14px;padding:12px;position:relative;min-height:0}
        .vs-stat .vs-label{font-size:var(--vs-stat-label-fs,10px);margin-bottom:6px;padding-right:28px}
        .vs-stat-value{font-size:var(--vs-stat-value-fs,24px);font-weight:800;color:#f8fbff;line-height:1.15;word-break:break-word}
        .vs-bars{display:none}
        .vs-status-icon{position:absolute;right:10px;top:10px;color:#22c55e}
        .vs-status-row{display:flex;align-items:center;gap:8px;margin-top:2px;min-width:0}
        .vs-clock{color:#94a3b8;flex:0 0 auto}
        .vs-progress-wrap{margin-top:10px;background:rgba(8,20,44,.55);border:1px solid rgba(148,163,184,.16);border-radius:14px;padding:12px}
        .vs-progress-head{display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:10px;flex-wrap:wrap}
        .vs-progress-left{display:flex;align-items:center;gap:8px;color:#dbe7ff;font-weight:700;font-size:var(--vs-progress-label-fs,14px);min-width:0;flex:1 1 140px;flex-wrap:wrap}
        .vs-bolt{color:#60a5fa;flex:0 0 auto}
        .vs-completed{color:#4ade80;font-weight:700}
        .vs-progress-pct{font-size:var(--vs-progress-pct-fs,18px);font-weight:800;color:#f8fbff;flex:0 0 auto}
        .vs-track{height:10px;background:rgba(148,163,184,.22);border-radius:999px;overflow:hidden;width:100%}
        .vs-fill{height:100%;background:linear-gradient(90deg,color-mix(in srgb,var(--vs-progress),#fff 10%),var(--vs-progress));border-radius:999px;max-width:100%}
        .vs-remaining{margin-top:8px;color:#9fb0d6;font-size:var(--vs-remaining-fs,13px)}
        @media (min-width: 420px){
            .vs-card{padding:16px}
            .vs-stats{grid-template-columns:1fr 1fr}
            .vs-bars{display:flex;position:absolute;right:10px;top:36px;align-items:flex-end;gap:4px;height:28px}
            .vs-bars span{width:5px;border-radius:4px;background:linear-gradient(180deg,color-mix(in srgb,var(--vs-accent),#fff 20%),var(--vs-accent));display:block}
            .vs-illus{display:block;width:110px;height:84px;flex:0 0 auto;position:relative}
            .vs-illus .cardA,.vs-illus .cardB{position:absolute;border-radius:12px;border:1px solid rgba(148,163,184,.25);background:linear-gradient(180deg,rgba(30,41,59,.9),rgba(15,23,42,.95));box-shadow:0 10px 25px rgba(2,8,23,.45)}
            .vs-illus .cardA{width:62px;height:40px;right:18px;top:22px;transform:rotate(-12deg)}
            .vs-illus .cardB{width:70px;height:44px;right:2px;top:12px;transform:rotate(8deg)}
            .vs-illus .chip{position:absolute;left:10px;top:10px;width:16px;height:12px;border-radius:3px;background:linear-gradient(180deg,#64748b,#475569)}
            .vs-illus .wmark{position:absolute;left:12px;bottom:8px;width:24px;height:4px;border-radius:99px;background:rgba(148,163,184,.35)}
            .vs-illus .check{position:absolute;right:0;bottom:0;width:26px;height:26px;border-radius:999px;background:linear-gradient(180deg,#34d399,#22c55e);display:flex;align-items:center;justify-content:center;border:2px solid #0b1a39}
        }
        @media (max-width: 360px){
            .vs-card{padding:12px;border-radius:16px}
            .vs-wallet-box{padding:9px 10px}
        }
    </style>

    <div class="vs-row">
        <div class="vs-title-wrap">
            <div class="vs-title-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 7L10.5 16.5L4 10" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div class="vs-title">{{ $d['card_title'] }}</div>
        </div>
        <div class="vs-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 7L10.5 16.5L4 10" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span>{{ $d['eligibility_badge_text'] }}</span>
        </div>
    </div>

    <div class="vs-wallet-section">
        <div class="vs-wallet-main">
            <div class="vs-label">{{ $d['connected_wallet_label'] }}</div>
            <div class="vs-wallet-box">
                <span title="{{ $d['wallet_display'] }}">{{ $d['wallet_display'] }}</span>
                <span class="vs-copy" title="Copy">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="9" y="9" width="10" height="10" rx="2" stroke="currentColor" stroke-width="2"/><rect x="5" y="5" width="10" height="10" rx="2" stroke="currentColor" stroke-width="2"/></svg>
                </span>
            </div>
        </div>
        <div class="vs-illus" aria-hidden="true">
            <div class="cardA"><div class="chip"></div><div class="wmark"></div></div>
            <div class="cardB"><div class="chip"></div><div class="wmark" style="width:34px"></div></div>
            <div class="check"><svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M20 7L10.5 16.5L4 10" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
        </div>
    </div>

    <div class="vs-refund">
        <div class="vs-refund-head">
            <span class="vs-dollar">$</span>
            <div class="vs-label" style="margin:0">{{ $d['total_refund_label'] }}</div>
        </div>
        <div class="vs-amount">{{ $d['total_refund_amount'] }}</div>
    </div>

    <div class="vs-stats">
        <div class="vs-stat">
            <div class="vs-label">{{ $d['signature_percentage_label'] }}</div>
            <div class="vs-stat-value">{{ $d['signature_percentage_value'] }}</div>
            <div class="vs-bars" aria-hidden="true">
                <span style="height:40%"></span><span style="height:65%"></span><span style="height:50%"></span><span style="height:85%"></span>
            </div>
        </div>
        <div class="vs-stat">
            <div class="vs-status-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" stroke="currentColor" stroke-width="2"/><path d="M9.5 12.5l1.8 1.8 3.7-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div class="vs-label">{{ $d['refund_status_label'] }}</div>
            <div class="vs-status-row">
                <span class="vs-clock" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/><path d="M12 8v5l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </span>
                <div class="vs-stat-value">{{ $d['refund_status_value'] }}</div>
            </div>
        </div>
    </div>

    <div class="vs-progress-wrap">
        <div class="vs-progress-head">
            <div class="vs-progress-left">
                <span class="vs-bolt" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M13 2L4 14h6l-1 8 9-12h-6l1-8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                </span>
                <span>{{ $d['signature_progress_label'] }}</span>
                <span class="vs-completed">{{ $d['signature_progress_status'] }}</span>
            </div>
            <div class="vs-progress-pct">{{ $d['signature_progress_percent'] }}</div>
        </div>
        <div class="vs-track"><div class="vs-fill" style="width: {{ $percent }}%"></div></div>
        <div class="vs-remaining">{{ $d['signature_progress_remaining'] }}</div>
    </div>
</div>
