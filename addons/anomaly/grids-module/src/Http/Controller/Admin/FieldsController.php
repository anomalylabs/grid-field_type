<?php namespace Anomaly\GridsModule\Http\Controller\Admin;

use Anomaly\Streams\Platform\Support\Authorizer;

/**
 * Class FieldsController
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class FieldsController extends \Anomaly\Streams\Platform\Http\Controller\FieldsController
{

    /**
     * The stream namespace.
     *
     * @var string
     */
    protected $namespace = 'grid';

    /**
     * Create a new FieldsController instance.
     *
     * @param Authorizer $authorizer
     */
    public function __construct(Authorizer $authorizer)
    {
        parent::__construct();

        $this->middleware(
            function ($request, $next) use ($authorizer) {
                if (!$authorizer->authorize('anomaly.module.grids::fields.manage')) {
                    abort(403);
                }

                return $next($request);
            }
        );
    }
}
