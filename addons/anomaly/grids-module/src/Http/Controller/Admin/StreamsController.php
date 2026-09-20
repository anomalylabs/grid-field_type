<?php namespace Anomaly\GridsModule\Http\Controller\Admin;

use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Anomaly\Streams\Platform\Stream\Form\StreamFormBuilder;
use Anomaly\Streams\Platform\Stream\Table\StreamTableBuilder;

/**
 * Class StreamsController
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class StreamsController extends AdminController
{

    /**
     * Return an index of grid streams.
     *
     * The builders administer the streams stream rather than
     * one of this module's own, so the permission convention
     * derives nothing and each is set explicitly.
     *
     * @param StreamTableBuilder $builder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(StreamTableBuilder $builder)
    {
        return $builder
            ->setNamespace('grid')
            ->setOption('permission', 'anomaly.module.grids::grids.read')
            ->setActions(
                [
                    'prompt' => [
                        'permission' => 'anomaly.module.grids::grids.delete',
                    ],
                ]
            )
            ->setButtons(
                [
                    'edit'        => [
                        'permission' => 'anomaly.module.grids::grids.write',
                    ],
                    'assignments' => [
                        'permission' => 'anomaly.module.grids::grids.fields',
                    ],
                ]
            )
            ->render();
    }

    /**
     * Create a new stream.
     *
     * @param StreamFormBuilder $builder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(StreamFormBuilder $builder)
    {
        return $builder
            ->setPrefix('grid_')
            ->setNamespace('grid')
            ->setOption('permission', 'anomaly.module.grids::grids.write')
            ->render();
    }

    /**
     * Edit an existing stream.
     *
     * @param StreamFormBuilder $builder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(StreamFormBuilder $builder)
    {
        return $builder
            ->setNamespace('grid')
            ->setOption('permission', 'anomaly.module.grids::grids.write')
            ->render($this->route->parameter('id'));
    }
}
