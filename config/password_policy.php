<?php

return [
    'recheck_seconds' => 300,
    // 'warn' shows a dismissible notice and never blocks a session; 'enforce' redirects until fixed.
    'mode' => env('PASSWORD_POLICY_MODE', 'warn'),
    // Date/time after which 'warn' turns into 'enforce'. Empty means never.
    'notice_until' => env('PASSWORD_POLICY_NOTICE_UNTIL'),
    'minimum_length' => 8,
    // OPTV2USER.PASSWORD is varchar(254); retain the shared application's storage format.
    'maximum_bytes' => 254,
    'patterns' => [
        ['pattern' => '[A-Za-z0-9]', 'message' => 'Include at least one letter (A–Z) or digit (0–9).'],
        ['pattern' => '^[^0-9]', 'message' => 'Do not start with a number.'],
    ],
];
