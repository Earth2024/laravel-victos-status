<?php

namespace Earth2024\VictosStatus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusRecord extends Model
{
    protected $fillable = [
        'user_id',
        'address',
        'card_title',
        'eligibility_badge_text',
        'connected_wallet_label',
        'wallet_display',
        'total_refund_label',
        'total_refund_amount',
        'refund_total',
        'amount_refunded',
        'total_eligible_amount',
        'eligible_amount_paid',
        'signature_percentage_label',
        'signature_percentage_value',
        'refund_status_label',
        'refund_status_value',
        'signature_progress_label',
        'signature_progress_status',
        'signature_progress_percent',
        'signature_progress_remaining',
        'card_bg_from',
        'card_bg_to',
        'card_border_color',
        'badge_bg_color',
        'badge_text_color',
        'accent_color',
        'progress_bar_color',
        'amount_color',
        'card_title_font_size',
        'badge_font_size',
        'section_label_font_size',
        'wallet_value_font_size',
        'amount_font_size',
        'stat_label_font_size',
        'stat_value_font_size',
        'progress_label_font_size',
        'progress_percent_font_size',
        'remaining_font_size',
    ];

    protected function casts(): array
    {
        return [
            'refund_total' => 'decimal:2',
            'amount_refunded' => 'decimal:2',
            'total_eligible_amount' => 'decimal:2',
            'eligible_amount_paid' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('victos-status.user_model'));
    }

    public function setAddressAttribute($value): void
    {
        $this->attributes['address'] = strtolower(trim((string) $value));
    }

    public function refundedPercentage(): float
    {
        $total = (float) ($this->total_eligible_amount ?? 0);
        $paid = (float) ($this->eligible_amount_paid ?? 0);
        if ($total <= 0) {
            return 0.0;
        }

        $percent = ($paid / $total) * 100;
        if ($percent < 0) {
            $percent = 0.0;
        }
        if ($percent > 100) {
            $percent = 100.0;
        }

        return round($percent, 2);
    }

    public function remainingPercentage(): float
    {
        return round(max(0.0, 100.0 - $this->refundedPercentage()), 2);
    }

    public function formatPercent(float $value): string
    {
        $text = number_format($value, 2, '.', '');
        $text = rtrim(rtrim($text, '0'), '.');

        return $text . '%';
    }

    public function formattedTotalRefundAmount(): string
    {
        if (!empty($this->total_refund_amount)) {
            return (string) $this->total_refund_amount;
        }

        $total = (float) ($this->refund_total ?? 0);

        return '$' . number_format($total, 2, '.', ',');
    }
}
