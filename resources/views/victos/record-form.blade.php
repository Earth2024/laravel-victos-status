<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mode === 'edit' ? 'Edit' : 'Create' }} Status Record - Victos</title>
    <style>
        *{box-sizing:border-box}body{margin:0;font:15px/1.5 Inter,system-ui,-apple-system,Segoe UI,Arial,sans-serif;background:#f1f5f9;color:#0f172a}
        .wrap{max-width:1120px;margin:0 auto;padding:24px 18px}.panel{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:22px;box-shadow:0 12px 32px rgba(15,23,42,.05)}
        h1{margin:0 0 8px;font-size:24px;letter-spacing:-.03em}h2{margin:20px 0 10px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,240px),1fr));gap:13px}
        label{display:block;font-size:13px;color:#334155;font-weight:650;margin-bottom:6px}input{width:100%;padding:10px 12px;border-radius:9px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font:inherit}
        input:focus{outline:3px solid #c7d2fe;border-color:#6366f1}.btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 14px;border-radius:9px;border:0;cursor:pointer;text-decoration:none;font-weight:650;font-size:14px}
        .btn-primary{background:#4f46e5;color:#fff}.btn-secondary{background:#eef2ff;color:#3730a3}.actions{display:flex;gap:8px;margin-top:16px;flex-wrap:wrap}
        .muted{color:#64748b;font-size:13px;margin-bottom:14px}.error{margin-bottom:16px;padding:11px 13px;border-radius:10px;background:#fee2e2;border:1px solid #fecaca;color:#991b1b}
        .section-title{margin-top:20px;margin-bottom:10px;font-size:16px;color:#0f172a}.hint{font-size:12px;color:#64748b;margin-top:4px}
        @media(max-width:600px){.wrap{padding:14px 10px}.panel{padding:17px}.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="wrap">
    <div class="panel">
        <h1>{{ $mode === 'edit' ? 'Edit' : 'Create' }} Status Record</h1>
        <p class="muted">Enter the address visitors will check. Percentages use eligible amount and eligible amount paid. Leave text/style fields blank to use your tenant defaults.</p>
        @if ($errors->any())
            <div class="error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $mode === 'edit' ? route('victos.records.update', $record) : route('victos.records.store') }}">
            @csrf
            @if ($mode === 'edit')
                @method('PUT')
            @endif

            <h2 class="section-title">Record &amp; Card Text</h2>
            <div class="grid">
                <div style="grid-column: 1 / -1;"><label>Address (lookup key, required)</label><input name="address" value="{{ old('address', $record->address) }}" required placeholder="e.g. 0x71C... or full address"></div>

                <div><label>Card title</label><input name="card_title" value="{{ old('card_title', $record->card_title) }}" placeholder="{{ $settings->card_title }}"></div>
                <div><label>Eligibility badge text</label><input name="eligibility_badge_text" value="{{ old('eligibility_badge_text', $record->eligibility_badge_text) }}" placeholder="{{ $settings->eligibility_badge_text }}"></div>

                <div><label>Connected wallet label</label><input name="connected_wallet_label" value="{{ old('connected_wallet_label', $record->connected_wallet_label) }}" placeholder="{{ $settings->connected_wallet_label }}"></div>
                <div><label>Wallet display value</label><input name="wallet_display" value="{{ old('wallet_display', $record->wallet_display) }}" placeholder="0x71C...92A4"></div>

                <div><label>Total refund label</label><input name="total_refund_label" value="{{ old('total_refund_label', $record->total_refund_label) }}" placeholder="{{ $settings->total_refund_label }}"></div>
                <div><label>Signature percentage label</label><input name="signature_percentage_label" value="{{ old('signature_percentage_label', $record->signature_percentage_label) }}" placeholder="{{ $settings->signature_percentage_label }}"></div>

                <div><label>Refund status label</label><input name="refund_status_label" value="{{ old('refund_status_label', $record->refund_status_label) }}" placeholder="{{ $settings->refund_status_label }}"></div>
                <div><label>Refund status value</label><input name="refund_status_value" value="{{ old('refund_status_value', $record->refund_status_value) }}" placeholder="Pending"></div>

                <div><label>Signature progress label</label><input name="signature_progress_label" value="{{ old('signature_progress_label', $record->signature_progress_label) }}" placeholder="{{ $settings->signature_progress_label }}"></div>
                <div><label>Signature progress status</label><input name="signature_progress_status" value="{{ old('signature_progress_status', $record->signature_progress_status) }}" placeholder="Completed / In progress"></div>
            </div>

            <h2 class="section-title">Refund Amounts (dynamic percentages)</h2>
            <p class="muted" style="margin-top:0;">Percent refunded and percent remaining are calculated from the eligible amounts below (no manual percentage entry). Total refund amount is kept as the displayed card amount.</p>
            <div class="grid">
                <div>
                    <label>Total refund amount (displayed on card)</label>
                    <input type="text" name="total_refund_amount" value="{{ old('total_refund_amount', $record->total_refund_amount ?? '') }}" placeholder="$1,250.00">
                    <div class="hint">Shown on the card as the total refund amount. Leave blank to auto-format from total eligible amount.</div>
                </div>
                <div>
                    <label>Total eligible amount (for percentage calculation)</label>
                    <input type="number" step="0.01" min="0" name="total_eligible_amount" value="{{ old('total_eligible_amount', $record->total_eligible_amount ?? 0) }}" required placeholder="1250.00">
                    <div class="hint">Used as the denominator when calculating refunded/remaining percentages.</div>
                </div>
                <div>
                    <label>Eligible amount paid (for percentage calculation)</label>
                    <input type="number" step="0.01" min="0" name="eligible_amount_paid" value="{{ old('eligible_amount_paid', $record->eligible_amount_paid ?? 0) }}" required placeholder="937.50">
                    <div class="hint">Used as the numerator when calculating refunded/remaining percentages.</div>
                </div>
            </div>

            <h2 class="section-title">Card Appearance (this record)</h2>
            <p class="muted" style="margin-top:0;">Customize how this specific card looks. Leave a field blank to use the global setting.</p>
            <div class="grid">
                <div><label>Card background (from)</label><input name="card_bg_from" value="{{ old('card_bg_from', $record->card_bg_from) }}" placeholder="{{ $settings->card_bg_from }}"></div>
                <div><label>Card background (to)</label><input name="card_bg_to" value="{{ old('card_bg_to', $record->card_bg_to) }}" placeholder="{{ $settings->card_bg_to }}"></div>
                <div><label>Card border color</label><input name="card_border_color" value="{{ old('card_border_color', $record->card_border_color) }}" placeholder="{{ $settings->card_border_color }}"></div>
                <div><label>Badge background color</label><input name="badge_bg_color" value="{{ old('badge_bg_color', $record->badge_bg_color) }}" placeholder="{{ $settings->badge_bg_color }}"></div>
                <div><label>Badge text color</label><input name="badge_text_color" value="{{ old('badge_text_color', $record->badge_text_color) }}" placeholder="{{ $settings->badge_text_color }}"></div>
                <div><label>Accent color</label><input name="accent_color" value="{{ old('accent_color', $record->accent_color) }}" placeholder="{{ $settings->accent_color }}"></div>
                <div><label>Progress bar color</label><input name="progress_bar_color" value="{{ old('progress_bar_color', $record->progress_bar_color) }}" placeholder="{{ $settings->progress_bar_color }}"></div>
                <div><label>Amount color</label><input name="amount_color" value="{{ old('amount_color', $record->amount_color) }}" placeholder="{{ $settings->amount_color }}"></div>
            </div>

            <h2 class="section-title">Card Font Sizes (this record)</h2>
            <p class="muted" style="margin-top:0;">Customize font sizes for each part of this card (e.g. 16px, 1rem). Leave blank to use the global setting.</p>
            <div class="grid">
                <div><label>Card title font size</label><input name="card_title_font_size" value="{{ old('card_title_font_size', $record->card_title_font_size) }}" placeholder="{{ $settings->card_title_font_size ?? '16px' }}"></div>
                <div><label>Badge font size</label><input name="badge_font_size" value="{{ old('badge_font_size', $record->badge_font_size) }}" placeholder="{{ $settings->badge_font_size ?? '12px' }}"></div>
                <div><label>Section label font size</label><input name="section_label_font_size" value="{{ old('section_label_font_size', $record->section_label_font_size) }}" placeholder="{{ $settings->section_label_font_size ?? '10px' }}"></div>
                <div><label>Wallet value font size</label><input name="wallet_value_font_size" value="{{ old('wallet_value_font_size', $record->wallet_value_font_size) }}" placeholder="{{ $settings->wallet_value_font_size ?? '14px' }}"></div>
                <div><label>Total refund amount font size</label><input name="amount_font_size" value="{{ old('amount_font_size', $record->amount_font_size) }}" placeholder="{{ $settings->amount_font_size ?? '28px' }}"></div>
                <div><label>Stat label font size</label><input name="stat_label_font_size" value="{{ old('stat_label_font_size', $record->stat_label_font_size) }}" placeholder="{{ $settings->stat_label_font_size ?? '10px' }}"></div>
                <div><label>Stat value font size</label><input name="stat_value_font_size" value="{{ old('stat_value_font_size', $record->stat_value_font_size) }}" placeholder="{{ $settings->stat_value_font_size ?? '24px' }}"></div>
                <div><label>Progress label font size</label><input name="progress_label_font_size" value="{{ old('progress_label_font_size', $record->progress_label_font_size) }}" placeholder="{{ $settings->progress_label_font_size ?? '14px' }}"></div>
                <div><label>Progress percent font size</label><input name="progress_percent_font_size" value="{{ old('progress_percent_font_size', $record->progress_percent_font_size) }}" placeholder="{{ $settings->progress_percent_font_size ?? '18px' }}"></div>
                <div><label>Remaining text font size</label><input name="remaining_font_size" value="{{ old('remaining_font_size', $record->remaining_font_size) }}" placeholder="{{ $settings->remaining_font_size ?? '13px' }}"></div>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">{{ $mode === 'edit' ? 'Update Record' : 'Create Record' }}</button>
                <a class="btn btn-secondary" href="{{ route('victos.dashboard') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
