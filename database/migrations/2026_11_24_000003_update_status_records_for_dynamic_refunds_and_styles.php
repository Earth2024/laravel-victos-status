<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('status_records', function (Blueprint $table) {
            if (!Schema::hasColumn('status_records', 'refund_total')) {
                $table->decimal('refund_total', 15, 2)->nullable()->after('total_refund_amount');
            }
            if (!Schema::hasColumn('status_records', 'amount_refunded')) {
                $table->decimal('amount_refunded', 15, 2)->nullable()->after('refund_total');
            }

            $styleColumns = [
                'card_bg_from',
                'card_bg_to',
                'card_border_color',
                'badge_bg_color',
                'badge_text_color',
                'accent_color',
                'progress_bar_color',
                'amount_color',
            ];

            foreach ($styleColumns as $column) {
                if (!Schema::hasColumn('status_records', $column)) {
                    $table->string($column)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('status_records', function (Blueprint $table) {
            $columns = [
                'refund_total',
                'amount_refunded',
                'card_bg_from',
                'card_bg_to',
                'card_border_color',
                'badge_bg_color',
                'badge_text_color',
                'accent_color',
                'progress_bar_color',
                'amount_color',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('status_records', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
