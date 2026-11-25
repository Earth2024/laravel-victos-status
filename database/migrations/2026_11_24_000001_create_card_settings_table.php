<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_settings', function (Blueprint $table) {
            $table->id();
            $table->string('card_title')->default('Example of information');
            $table->string('eligibility_badge_text')->default('Eligible');
            $table->string('connected_wallet_label')->default('CONNECTED WALLET');
            $table->string('total_refund_label')->default('YOUR TOTAL REFUND');
            $table->string('signature_percentage_label')->default('Signature percentage');
            $table->string('refund_status_label')->default('Refund status');
            $table->string('signature_progress_label')->default('Signature progress');
            $table->string('card_bg_from')->default('#071a3d');
            $table->string('card_bg_to')->default('#06142e');
            $table->string('card_border_color')->default('rgba(99, 102, 241, 0.35)');
            $table->string('badge_bg_color')->default('#22c55e');
            $table->string('badge_text_color')->default('#ffffff');
            $table->string('accent_color')->default('#7c3aed');
            $table->string('progress_bar_color')->default('#22c55e');
            $table->string('amount_color')->default('#ffffff');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_settings');
    }
};
