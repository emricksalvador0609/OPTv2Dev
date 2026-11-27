<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sign-in request ceiling
    |--------------------------------------------------------------------------
    |
    | Per client address, not per account. This exists only to stop a flood
    | from costing a database query each: a human cannot reach the default,
    | and the per-account lockout already limits guessing to 3 tries.
    | Applied before any query, so an over-limit request is cheap.
    |
    | 600 is above the number of active accounts (636), so even every account
    | signing in inside one minute through a single shared office address
    | stays under it.
    |
    */

    'max_requests' => (int) env('LOGIN_THROTTLE_MAX', 600),

    'decay_seconds' => (int) env('LOGIN_THROTTLE_DECAY', 60),

    /*
    | One auth.log line per address per window. Keeps the fail2ban input
    | bounded even while a flood is rejected.
    */
    'log_seconds' => (int) env('LOGIN_LOG_INTERVAL', 60),

    /*
    | Where the security lines are written: storage/logs/auth-YYYY-MM-DD.log,
    | UTC timestamp. fail2ban reads these. Directory must exist and be
    | writable by the web user.
    */
    'log_dir' => env('LOGIN_LOG_DIR', storage_path('logs')),

];
