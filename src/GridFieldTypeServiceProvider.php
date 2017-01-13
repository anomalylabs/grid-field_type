<?php namespace Anomaly\GridFieldType;

use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Addon\AddonIntegrator;
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
        'streams/field_type/grid/choose/{field}'        => 'Anomaly\GridFieldType\Http\Controller\GridController@choose',
        'streams/field_type/grid/form/{field}/{stream}' => 'Anomaly\GridFieldType\Http\Controller\GridController@form',
    ];

    /**
     * Register the addon.
     *
     * @param AddonIntegrator $integrator
     * @param AddonCollection $addons
     */
    public function register(AddonIntegrator $integrator, AddonCollection $addons)
    {
        $addon = $integrator->register(
            __DIR__ . '/../addons/anomaly/grids-module/',
            'anomaly.module.grids',
            true,
            true
        );

        $addons->push($addon);
    }
}
