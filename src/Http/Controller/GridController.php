<?php namespace Anomaly\GridFieldType\Http\Controller;

use Anomaly\GridFieldType\GridFieldType;
use Anomaly\Streams\Platform\Field\Contract\FieldInterface;
use Anomaly\Streams\Platform\Field\Contract\FieldRepositoryInterface;
use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;
use Anomaly\Streams\Platform\Ui\Form\Command\LoadForm;
use Anomaly\Streams\Platform\Ui\Form\Command\MakeForm;
use Anomaly\Streams\Platform\Ui\Form\Command\PopulateFields;
use Anomaly\Streams\Platform\Ui\Form\Command\SetFormResponse;

/**
 * Class GridController
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GridController extends AdminController
{

    /**
     * Choose what kind of row to add.
     *
     * @param FieldRepositoryInterface  $fields
     * @param StreamRepositoryInterface $streams
     * @param                           $field
     * @return \Illuminate\Contracts\View\View|mixed
     */
    public function choose(FieldRepositoryInterface $fields, StreamRepositoryInterface $streams, $field)
    {
        /* @var FieldInterface $field */
        if (!$field = $fields->find($field)) {
            abort(404);
        }

        /* @var GridFieldType $type */
        $type = $field->getType();

        if (!$type instanceof GridFieldType) {
            abort(404);
        }

        return $this->view->make(
            'anomaly.field_type.grid::choose',
            [
                'grids' => array_map(
                    function ($model) {
                        return app($model);
                    },
                    $this->related($type, $streams)
                ),
            ]
        );
    }

    /**
     * Return a form row.
     *
     * @param FieldRepositoryInterface  $fields
     * @param StreamRepositoryInterface $streams
     * @param                           $field
     * @param                           $stream
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function form(
        FieldRepositoryInterface $fields,
        StreamRepositoryInterface $streams,
        $field,
        $stream
    ) {
        /* @var FieldInterface $field */
        if (!$field = $fields->find($field)) {
            abort(404);
        }

        /* @var GridFieldType $type */
        $type = $field->getType();

        if (!$type instanceof GridFieldType) {
            abort(404);
        }

        /* @var StreamInterface $stream */
        if (!$stream = $streams->find($stream)) {
            abort(404);
        }

        /*
         * Only the streams this field is configured to
         * relate to. Without this the parameter addresses
         * every stream in the installation.
         */
        if (!in_array($stream->getEntryModelName(), $this->related($type, $streams))) {
            abort(404);
        }

        $type->setPrefix($this->request->get('prefix'));

        $builder = $type
            ->form($field, $stream, $this->request->get('instance'))
            ->addFormData('field_type', $type);

        /*
         * Build and render the markup only. Calling render()
         * would run make() -> post(), which saves the form.
         */
        $builder->build();

        dispatch_sync(new PopulateFields($builder));
        dispatch_sync(new LoadForm($builder));
        dispatch_sync(new MakeForm($builder));
        dispatch_sync(new SetFormResponse($builder));

        return $builder->getFormResponse();
    }

    /**
     * Return the entry models the field may relate to.
     *
     * @param  GridFieldType            $type
     * @param  StreamRepositoryInterface $streams
     * @return array
     */
    protected function related(GridFieldType $type, StreamRepositoryInterface $streams)
    {
        if ($related = (array)$type->config('related', [])) {
            return $related;
        }

        return array_map(
            function (StreamInterface $stream) {
                return $stream->getEntryModelName();
            },
            $streams->findAllByNamespace('grid')->all()
        );
    }
}
