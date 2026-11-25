<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_records', function (Blueprint $table) {
            $table->id();
            $table->string('address')->unique();
            $table->string('card_title')->nullable();
            $table->string('eligibility_badge_text')->nullable();
            $table->string('connected_wallet_label')->nullable();
            $table->string('wallet_display')->nullable();
            $table->string('total_refund_label')->nullable();
            $table->string('total_refund_amount')->nullable();
            $table->string('signature_percentage_label')->nullable();
            $table->string('signature_percentage_value')->nullable();
            $table->string('refund_status_label')->nullable();
            $table->string('refund_status_value')->nullable();
            $table->string('signature_progress_label')->nullable();
            $table->string('signature_progress_status')->nullable();
            $table->string('signature_progress_percent')->nullable();
            $table->string('signature_progress_remaining')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_records');
    }
};
