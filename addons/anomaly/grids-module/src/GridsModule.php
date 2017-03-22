<?php namespace Anomaly\GridsModule;

use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class GridsModule
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GridsModule extends Module
{

    /**
     * The module icon.
     *
     * @var string
     */
    protected $icon = 'fa fa-th';

    /**
     * The addon sections.
     *
     * @var array
     */
    protected $sections = [
        'grids'  => [
            'buttons'  => [
                'new_grid',
            ],
            'sections' => [
                'assignments' => [
                    'hidden'  => true,
                    'href'    => 'admin/grids/assignments/{request.route.parameters.stream}',
                    'buttons' => [
                        'assign_fields' => [
                            'data-toggle' => 'modal',
                            'data-target' => '#modal',
                            'enabled'     => 'admin/grids/assignments/*',
                            'href'        => 'admin/grids/assignments/{request.route.parameters.stream}/choose',
                        ],
                    ],
                ],
            ],
        ],
        'fields' => [
            'buttons' => [
                'new_field' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'href'        => 'admin/grids/fields/choose',
                ],
            ],
        ],
    ];
}
