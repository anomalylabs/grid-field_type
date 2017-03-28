<?php

return [
    'related' => [
        'label'        => 'Grids',
        'warning'      => 'If none are selected all Grids will be available.',
        'instructions' => 'Specify the related <a href="' . url(
                'admin/grids'
            ) . '" target="_blank">grids</a>.',
    ],
    'add_row' => [
        'label'        => 'Add Row',
        'instructions' => 'Specify custom text for the "Add Row" button.',
        'placeholder'  => 'Add Row',
    ],
    'min'     => [
        'label'        => 'Minimum Items',
        'instructions' => 'Specify the minimum number of allowed items.',
    ],
    'max'     => [
        'label'        => 'Maximum Items',
        'instructions' => 'Specify the maximum number of allowed items.',
    ],
];
