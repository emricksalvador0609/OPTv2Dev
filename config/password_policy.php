<?php

return [
    'recheck_seconds' => 300,
    'minimum_length' => 12,
    // OPTV2USER.PASSWORD is varchar(254); retain the shared application's storage format.
    'maximum_bytes' => 254,
    'patterns' => [
        ['pattern' => '[A-Za-z0-9]', 'message' => 'Include at least one letter (A–Z) or digit (0–9).'],
        ['pattern' => '^[^0-9]', 'message' => 'Do not start with a number.'],
    ],
];
