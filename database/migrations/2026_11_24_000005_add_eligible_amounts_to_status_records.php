<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('status_records', function (Blueprint $table) {
            if (!Schema::hasColumn('status_records', 'total_eligible_amount')) {
                $table->decimal('total_eligible_amount', 15, 2)->nullable()->after('amount_refunded');
            }
            if (!Schema::hasColumn('status_records', 'eligible_amount_paid')) {
                $table->decimal('eligible_amount_paid', 15, 2)->nullable()->after('total_eligible_amount');
            }
        });

        DB::table('status_records')
            ->whereNull('total_eligible_amount')
            ->whereNotNull('refund_total')
            ->update(['total_eligible_amount' => DB::raw('refund_total')]);

        DB::table('status_records')
            ->whereNull('eligible_amount_paid')
            ->whereNotNull('amount_refunded')
            ->update(['eligible_amount_paid' => DB::raw('amount_refunded')]);
    }

    public function down(): void
    {
        Schema::table('status_records', function (Blueprint $table) {
            $columns = ['total_eligible_amount', 'eligible_amount_paid'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('status_records', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
