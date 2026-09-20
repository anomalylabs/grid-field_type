<?php

return [

    /*
     * Stream namespaces a grid may not relate to.
     *
     * A grid renders and saves entries of the related
     * stream through its parent form, which does not consult
     * the owning module's permissions. Grids are for
     * content, so the streams that carry accounts, settings
     * and platform internals are refused.
     */
    'protected' => [
        'users',
        'settings',
        'preferences',
        'configuration',
        'streams_utilities',
    ],
];
