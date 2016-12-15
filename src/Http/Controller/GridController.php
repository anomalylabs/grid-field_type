<?php namespace Anomaly\GridFieldType\Http\Controller;

use Anomaly\GridFieldType\GridFieldType;
use Anomaly\Streams\Platform\Field\Contract\FieldInterface;
use Anomaly\Streams\Platform\Field\Contract\FieldRepositoryInterface;
use Anomaly\Streams\Platform\Http\Controller\PublicController;

/**
 * Class GridController
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GridController extends PublicController
{

    /**
     * Choose what kind of row to add.
     *
     * @param FieldRepositoryInterface $fields
     * @param                          $field
     * @return \Illuminate\Contracts\View\View|mixed
     */
    public function choose(FieldRepositoryInterface $fields, $field)
    {
        /* @var FieldInterface $field */
        $field = $fields->find($field);

        /* @var GridFieldType $type */
        $type = $field->getType();

        return $this->view->make(
            'anomaly.field_type.grid::choose',
            [
                'types' => [],
            ]
        );
    }

    /**
     * Return a form row.
     *
     * @param FieldRepositoryInterface $fields
     * @param                          $field
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function form(FieldRepositoryInterface $fields, $field)
    {
        /* @var FieldInterface $field */
        $field = $fields->find($field);

        /* @var GridFieldType $type */
        $type = $field->getType();

        return $type
            ->form($field, $this->request->get('instance'))
            ->addFormData('field_type', $type)
            ->render();
    }
}
