<?php namespace Anomaly\GridFieldType\Command;

use Anomaly\GridFieldType\GridFieldType;
use Anomaly\Streams\Platform\Field\Contract\FieldInterface;
use Anomaly\Streams\Platform\Field\Contract\FieldRepositoryInterface;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;
use Anomaly\Streams\Platform\Ui\Form\Multiple\MultipleFormBuilder;
use Illuminate\Http\Request;

/**
 * Class GetMultiformFromPost
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GetMultiformFromPost
{

    /**
     * The field type instance.
     *
     * @var GridFieldType
     */
    protected $fieldType;

    /**
     * Create a new GetMultiformFromPost instance.
     *
     * @param GridFieldType $fieldType
     */
    public function __construct(GridFieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    /**
     * Handle the command.
     *
     * @param StreamRepositoryInterface $streams
     * @param FieldRepositoryInterface  $fields
     * @param MultipleFormBuilder       $forms
     * @param Request                   $request
     * @return MultipleFormBuilder|null
     */
    public function handle(StreamRepositoryInterface $streams, FieldRepositoryInterface $fields, MultipleFormBuilder $forms, Request $request)
    {
        if (!$request->has($this->fieldType->getFieldName())) {
            return null;
        }

        /* @var FieldInterface $field */
        if (!$field = $fields->find($this->fieldType->id())) {
            return null;
        }

        $related  = $this->related($streams);
        $attached = $this->attached();

        foreach ((array)$request->get($this->fieldType->getFieldName()) as $item) {

            $entry    = array_get((array)$item, 'entry');
            $instance = array_get((array)$item, 'instance');

            /* @var StreamInterface $stream */
            if (!$stream = $streams->find(array_get((array)$item, 'stream'))) {
                continue;
            }

            $model = $stream->getBoundEntryModelName();

            if (!$this->fieldType->allowedRelated($model)) {
                continue;
            }

            /*
             * An existing row is identified by the entry it is
             * attached to, a new one by the streams this field
             * relates to. Without either the posted parameter
             * addresses every stream in the installation.
             */
            if ($entry) {

                if (!in_array($model . '|' . $entry, $attached)) {
                    continue;
                }
            } elseif (!in_array($model, $related)) {
                continue;
            }

            /* @var GridFieldType $type */
            $type = $field->getType();

            $type->setPrefix($this->fieldType->getPrefix());

            $form = $type->form($field, $stream, $instance);

            if ($entry) {
                $form->setEntry($entry);
            }

            $form->build();

            $form->setReadOnly($this->fieldType->isReadOnly());

            $forms->addForm($this->fieldType->getFieldName() . '_' . $instance, $form);
        }

        $forms->setOption('success_message', false);

        return $forms;
    }

    /**
     * Return the entry models the field may relate to.
     *
     * @param  StreamRepositoryInterface $streams
     * @return array
     */
    protected function related(StreamRepositoryInterface $streams)
    {
        return $this->fieldType->relatedModels($streams);
    }

    /**
     * Return the rows already attached to the entry,
     * keyed as "<entry type>|<entry id>".
     *
     * @return array
     */
    protected function attached()
    {
        $entry = $this->fieldType->getEntry();

        if (!$entry || !$entry->getId()) {
            return [];
        }

        return $this->fieldType
            ->getRelatedModel()
            ->newQuery()
            ->getQuery()
            ->where('related_id', $entry->getId())
            ->get(['entry_id', 'entry_type'])
            ->map(
                function ($row) {
                    return $row->entry_type . '|' . $row->entry_id;
                }
            )
            ->all();
    }
}
