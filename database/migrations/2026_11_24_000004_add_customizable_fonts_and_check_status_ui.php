<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('card_settings', function (Blueprint $table) {
            $columns = [
                'check_status_button_text' => ['type' => 'string', 'default' => 'Check Status'],
                'check_status_button_text_color' => ['type' => 'string', 'default' => '#ffffff'],
                'check_status_button_bg_color' => ['type' => 'string', 'default' => '#2563eb'],
                'check_status_button_font_size' => ['type' => 'string', 'default' => '14px'],
                'modal_header_text' => ['type' => 'string', 'default' => 'Check Status'],
                'modal_address_label_text' => ['type' => 'string', 'default' => 'Enter your address'],
                'modal_submit_text' => ['type' => 'string', 'default' => 'Check Status'],
                'card_title_font_size' => ['type' => 'string', 'default' => '16px'],
                'badge_font_size' => ['type' => 'string', 'default' => '12px'],
                'section_label_font_size' => ['type' => 'string', 'default' => '10px'],
                'wallet_value_font_size' => ['type' => 'string', 'default' => '14px'],
                'amount_font_size' => ['type' => 'string', 'default' => '28px'],
                'stat_label_font_size' => ['type' => 'string', 'default' => '10px'],
                'stat_value_font_size' => ['type' => 'string', 'default' => '24px'],
                'progress_label_font_size' => ['type' => 'string', 'default' => '14px'],
                'progress_percent_font_size' => ['type' => 'string', 'default' => '18px'],
                'remaining_font_size' => ['type' => 'string', 'default' => '13px'],
            ];

            foreach ($columns as $name => $def) {
                if (!Schema::hasColumn('card_settings', $name)) {
                    $table->string($name)->default($def['default']);
                }
            }
        });

        Schema::table('status_records', function (Blueprint $table) {
            $fontColumns = [
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

            foreach ($fontColumns as $name) {
                if (!Schema::hasColumn('status_records', $name)) {
                    $table->string($name)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('card_settings', function (Blueprint $table) {
            $columns = [
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
            foreach ($columns as $column) {
                if (Schema::hasColumn('card_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('status_records', function (Blueprint $table) {
            $columns = [
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
            foreach ($columns as $column) {
                if (Schema::hasColumn('status_records', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
