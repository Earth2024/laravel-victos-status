<?php

namespace Earth2024\VictosStatus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardSetting extends Model
{
    protected $fillable = [
        'user_id',
        'card_title',
        'eligibility_badge_text',
        'connected_wallet_label',
        'total_refund_label',
        'signature_percentage_label',
        'refund_status_label',
        'signature_progress_label',
        'card_bg_from',
        'card_bg_to',
        'card_border_color',
        'badge_bg_color',
        'badge_text_color',
        'accent_color',
        'progress_bar_color',
        'amount_color',
        'check_status_button_text',
        'check_status_button_text_color',
        'check_status_button_bg_color',
        'check_status_button_font_size',
        'modal_header_text',
        'modal_address_label_text',
        'modal_submit_text',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('victos-status.user_model'));
    }

    public static function forUser(Model $user): self
    {
        return static::query()->firstOrCreate(['user_id' => $user->id], [
            'card_title' => 'Example of information',
            'eligibility_badge_text' => 'Eligible',
            'connected_wallet_label' => 'CONNECTED WALLET',
            'total_refund_label' => 'YOUR TOTAL REFUND',
            'signature_percentage_label' => 'Signature percentage',
            'refund_status_label' => 'Refund status',
            'signature_progress_label' => 'Signature progress',
            'card_bg_from' => '#071a3d',
            'card_bg_to' => '#06142e',
            'card_border_color' => 'rgba(99, 102, 241, 0.35)',
            'badge_bg_color' => '#22c55e',
            'badge_text_color' => '#ffffff',
            'accent_color' => '#7c3aed',
            'progress_bar_color' => '#22c55e',
            'amount_color' => '#ffffff',
            'check_status_button_text' => 'Check Status',
            'check_status_button_text_color' => '#ffffff',
            'check_status_button_bg_color' => '#2563eb',
            'check_status_button_font_size' => '14px',
            'modal_header_text' => 'Check Status',
            'modal_address_label_text' => 'Enter your address',
            'modal_submit_text' => 'Check Status',
            'card_title_font_size' => '16px',
            'badge_font_size' => '12px',
            'section_label_font_size' => '10px',
            'wallet_value_font_size' => '14px',
            'amount_font_size' => '28px',
            'stat_label_font_size' => '10px',
            'stat_value_font_size' => '24px',
            'progress_label_font_size' => '14px',
            'progress_percent_font_size' => '18px',
            'remaining_font_size' => '13px',
        ]);
    }
}
