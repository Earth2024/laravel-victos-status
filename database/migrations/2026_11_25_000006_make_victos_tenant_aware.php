<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin');
            $table->boolean('is_active')->default(true);
            $table->string('public_tenant_key', 64)->nullable()->unique();
            $table->json('allowed_domains')->nullable();
        });

        $superAdmin = DB::table('users')
            ->where('email', 'josearmstrong2026@gmail.com')
            ->first();
        $superAdmin ??= DB::table('users')->orderBy('id')->first();

        foreach (DB::table('users')->whereNull('public_tenant_key')->get(['id']) as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'public_tenant_key' => Str::random(48),
            ]);
        }

        if ($superAdmin) {
            DB::table('users')->where('id', $superAdmin->id)->update(['role' => 'super_admin']);
        }

        Schema::table('card_settings', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('status_records', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
        });

        if ($superAdmin) {
            DB::table('card_settings')->whereNull('user_id')->update(['user_id' => $superAdmin->id]);
            DB::table('status_records')->whereNull('user_id')->update(['user_id' => $superAdmin->id]);
        }

        Schema::table('status_records', function (Blueprint $table) {
            $table->dropUnique('status_records_address_unique');
            $table->unique(['user_id', 'address']);
        });

        Schema::table('card_settings', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('status_records', function (Blueprint $table) {
            $table->dropUnique('status_records_user_id_address_unique');
            $table->unique('address');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('card_settings', function (Blueprint $table) {
            $table->dropUnique('card_settings_user_id_unique');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_public_tenant_key_unique');
            $table->dropColumn(['role', 'is_active', 'public_tenant_key', 'allowed_domains']);
        });
    }
};
