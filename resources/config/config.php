<?php

use Anomaly\GridFieldType\Support\Config\StreamsOptions;

return [
    'related' => [
        'type'   => 'anomaly.field_type.checkboxes',
        'config' => [
            'handler' => StreamsOptions::class,
        ],
    ],
    'add_row' => [
        'type' => 'anomaly.field_type.text',
    ],
    'min'     => [
        'type'   => 'anomaly.field_type.integer',
        'config' => [
            'min' => 1,
        ],
    ],
    'max'     => [
        'type'   => 'anomaly.field_type.integer',
        'config' => [
            'min' => 1,
        ],
    ],
];
