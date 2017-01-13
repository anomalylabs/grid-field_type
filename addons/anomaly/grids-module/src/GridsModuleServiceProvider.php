<?php namespace Anomaly\GridsModule;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class GridsModuleServiceProvider
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GridsModuleServiceProvider extends AddonServiceProvider
{

    /**
     * The addon routes.
     *
     * @var array
     */
    protected $routes = [
        'admin/grids'                                => 'Anomaly\GridsModule\Http\Controller\Admin\StreamsController@index',
        'admin/grids/create'                         => 'Anomaly\GridsModule\Http\Controller\Admin\StreamsController@create',
        'admin/grids/edit/{id}'                      => 'Anomaly\GridsModule\Http\Controller\Admin\StreamsController@edit',
        'admin/grids/fields'                         => 'Anomaly\GridsModule\Http\Controller\Admin\FieldsController@index',
        'admin/grids/fields/choose'                  => 'Anomaly\GridsModule\Http\Controller\Admin\FieldsController@choose',
        'admin/grids/fields/create'                  => 'Anomaly\GridsModule\Http\Controller\Admin\FieldsController@create',
        'admin/grids/fields/edit/{id}'               => 'Anomaly\GridsModule\Http\Controller\Admin\FieldsController@edit',
        'admin/grids/assignments/{stream}'           => 'Anomaly\GridsModule\Http\Controller\Admin\AssignmentsController@index',
        'admin/grids/assignments/{stream}/choose'    => 'Anomaly\GridsModule\Http\Controller\Admin\AssignmentsController@choose',
        'admin/grids/assignments/{stream}/create'    => 'Anomaly\GridsModule\Http\Controller\Admin\AssignmentsController@create',
        'admin/grids/assignments/{stream}/edit/{id}' => 'Anomaly\GridsModule\Http\Controller\Admin\AssignmentsController@edit',
    ];
}
