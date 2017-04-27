<?php namespace Anomaly\GridsModule;

use Anomaly\GridsModule\Http\Controller\Admin\AssignmentsController;
use Anomaly\GridsModule\Http\Controller\Admin\FieldsController;
use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Anomaly\Streams\Platform\Assignment\AssignmentRouter;
use Anomaly\Streams\Platform\Field\FieldRouter;

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
        'admin/grids'           => 'Anomaly\GridsModule\Http\Controller\Admin\StreamsController@index',
        'admin/grids/create'    => 'Anomaly\GridsModule\Http\Controller\Admin\StreamsController@create',
        'admin/grids/edit/{id}' => 'Anomaly\GridsModule\Http\Controller\Admin\StreamsController@edit',
    ];

    /**
     * Map the addon.
     *
     * @param FieldRouter      $fields
     * @param AssignmentRouter $assignments
     */
    public function map(FieldRouter $fields, AssignmentRouter $assignments)
    {
        $fields->route($this->addon, FieldsController::class);
        $assignments->route($this->addon, AssignmentsController::class);
    }
}
