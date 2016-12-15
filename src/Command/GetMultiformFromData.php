<?php namespace Anomaly\GridFieldType\Command;

use Anomaly\GridFieldType\GridFieldType;
use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Entry\EntryCollection;
use Anomaly\Streams\Platform\Field\Contract\FieldInterface;
use Anomaly\Streams\Platform\Field\Contract\FieldRepositoryInterface;
use Anomaly\Streams\Platform\Ui\Form\Multiple\MultipleFormBuilder;

/**
 * Class GetMultiformFromData
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GetMultiformFromData
{

    /**
     * The field type instance.
     *
     * @var GridFieldType
     */
    protected $fieldType;

    /**
     * Create a new GetMultiformFromData instance.
     *
     * @param GridFieldType $fieldType
     */
    public function __construct(GridFieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    /**
     * Get the multiple form builder from the value.
     *
     * @param FieldRepositoryInterface $fields
     * @param MultipleFormBuilder      $forms
     * @return MultipleFormBuilder|null
     */
    public function handle(FieldRepositoryInterface $fields, MultipleFormBuilder $forms)
    {
        /* @var EntryCollection $value */
        if (!$value = $this->fieldType->getValue()) {
            return null;
        }

        foreach ($value as $item) {

            /* @var FieldInterface $field */
            if (!$field = $fields->find($item['field'])) {
                continue;
            }

            /* @var GridFieldType $type */
            $type = $field->getType();

            $form = $type->form($field, $item['instance']);

            if ($item['entry']) {
                $form->setEntry($item['entry']);
            }

            $forms->addForm($this->fieldType->getFieldName() . '_' . $item['instance'], $form);
        }

        return $forms;
    }
}
