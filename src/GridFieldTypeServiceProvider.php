<?php namespace Anomaly\GridFieldType;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class GridFieldTypeServiceProvider
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\GridFieldType
 */
class GridFieldTypeServiceProvider extends AddonServiceProvider
{

    /**
     * The addon routes.
     *
     * @var array
     */
    protected $routes = [
        'streams/field_type/grid/choose/{field}' => 'Anomaly\GridFieldType\Http\Controller\GridController@choose',
        'streams/field_type/grid/form/{field}'   => 'Anomaly\GridFieldType\Http\Controller\GridController@form',
    ];
}
