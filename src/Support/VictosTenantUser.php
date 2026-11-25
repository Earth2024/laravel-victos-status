<?php

namespace Earth2024\VictosStatus\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VictosTenantUser
{
    public static function fill(Model $user, array $attributes): void
    {
        $casts = $user->getCasts();
        if (array_key_exists('allowed_domains', $attributes)
            && !in_array($casts['allowed_domains'] ?? null, ['array', 'json', 'collection'], true)) {
            $attributes['allowed_domains'] = json_encode($attributes['allowed_domains'], JSON_THROW_ON_ERROR);
        }

        if (empty($attributes['public_tenant_key']) && empty($user->getAttribute('public_tenant_key'))) {
            $attributes['public_tenant_key'] = Str::random(48);
        }

        $user->forceFill($attributes);
    }
}
