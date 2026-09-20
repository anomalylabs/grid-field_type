<?php namespace Anomaly\GridFieldType\Command;

use Anomaly\GridFieldType\GridFieldType;
use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Entry\EntryCollection;
use Anomaly\Streams\Platform\Field\Contract\FieldInterface;
use Anomaly\Streams\Platform\Field\Contract\FieldRepositoryInterface;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;
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
     * @param StreamRepositoryInterface $streams
     * @param FieldRepositoryInterface  $fields
     * @param MultipleFormBuilder       $forms
     * @return MultipleFormBuilder|null
     */
    public function handle(StreamRepositoryInterface $streams, FieldRepositoryInterface $fields, MultipleFormBuilder $forms)
    {
        /* @var EntryCollection $value */
        if (!$value = $this->fieldType->getValue()) {
            return null;
        }

        /* @var FieldInterface $field */
        if (!$field = $fields->find($this->fieldType->id())) {
            return null;
        }

        foreach ((array)$value as $item) {

            $entry    = array_get((array)$item, 'entry');
            $instance = array_get((array)$item, 'instance');

            /* @var StreamInterface $stream */
            if (!$stream = $streams->find(array_get((array)$item, 'stream'))) {
                continue;
            }

            /* @var GridFieldType $type */
            $type = $field->getType();

            $type->setPrefix($this->fieldType->getPrefix());

            $form = $type->form($field, $stream, $instance);

            if ($entry) {
                $form->setEntry($entry);
            }

            $form->setReadOnly($this->fieldType->isReadOnly());

            $forms->addForm($this->fieldType->getFieldName() . '_' . $instance, $form);
        }

        $forms->setOption('success_message', false);

        return $forms;
    }
}
