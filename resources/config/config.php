<?php

use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;

return [
    'related' => [
        'type'   => 'anomaly.field_type.checkboxes',
        'config' => [
            'options' => function (\Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface $streams) {

                $options = [];

                /* @var StreamInterface as $stream */
                foreach ($streams->findAllByNamespace('grid') as $stream) {
                    $options[$stream->getEntryModelName()] = $stream->getName();
                }

                ksort($options);

                return $options;
            },
        ],
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
