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
     * Return a form row.
     *
     * @param FieldRepositoryInterface $fields
     * @param                          $field
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
