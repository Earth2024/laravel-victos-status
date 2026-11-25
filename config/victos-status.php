<?php

return [
    'user_model' => env('VICTOS_STATUS_USER_MODEL', App\Models\User::class),
    'admin_prefix' => env('VICTOS_STATUS_ADMIN_PREFIX', 'victos'),
    'status_prefix' => env('VICTOS_STATUS_PUBLIC_PREFIX', 'check-status'),
    'admin_middleware' => ['web', \Earth2024\VictosStatus\Http\Middleware\AuthenticateVictosAdmin::class],
    'guest_middleware' => ['web'],
    'public_middleware' => ['web', 'throttle:30,1'],
];
