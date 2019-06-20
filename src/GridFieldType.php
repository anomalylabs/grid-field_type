<?php namespace Anomaly\GridFieldType;

use Anomaly\GridFieldType\Command\GetMultiformFromPost;
use Anomaly\GridFieldType\Command\GetMultiformFromValue;
use Anomaly\GridFieldType\Grid\GridCollection;
use Anomaly\GridFieldType\Grid\GridModel;
use Anomaly\GridFieldType\Grid\GridRelation;
use Anomaly\GridFieldType\Validation\ValidateGrid;
use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Field\Contract\FieldInterface;
use Anomaly\Streams\Platform\Model\EloquentModel;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Ui\Form\FormBuilder;
use Anomaly\Streams\Platform\Ui\Form\Multiple\MultipleFormBuilder;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GridFieldType
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GridFieldType extends FieldType
{

    /**
     * The input class.
     *
     * @var string
     */
    protected $class = 'grid-container';

    /**
     * No database column.
     *
     * @var bool
     */
    protected $columnType = false;

    /**
     * The input view.
     *
     * @var string
     */
    protected $inputView = 'anomaly.field_type.grid::input';

    /**
     * The filter view.
     *
     * @var string
     */
    protected $filterView = 'anomaly.field_type.grid::filter';

    /**
     * The field rules.
     *
     * @var array
     */
    protected $rules = [
        'array',
        'grid',
    ];

    /**
     * The field validators.
     *
     * @var array
     */
    protected $validators = [
        'grid' => [
            'message' => false,
            'handler' => ValidateGrid::class,
        ],
    ];

    /**
     * The service container.
     *
     * @var Container
     */
    protected $container;

    /**
     * Create a new GridFieldType instance.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Return the field ID.
     *
     * @return int
     */
    public function id()
    {
        return $this->entry->getField($this->getField())->getId();
    }

    /**
     * Get the relation.
     *
     * @return GridRelation
     */
    public function getRelation()
    {
        $entry = $this->getEntry();
        $model = $this->getRelatedModel();

        return (new GridRelation($model->newQuery(), $entry, $model->getTable() . '.' . 'related_id', 'id'))
            ->orderBy($this->getPivotTableName() . '.sort_order', 'ASC');
    }

    /**
     * Get the pivot table.
     *
     * @return string
     */
    public function getPivotTableName()
    {
        return $this->entry->getTableName() . '_' . $this->getField();
    }

    /**
     * Get the related table.
     *
     * @return string
     */
    public function getRelatedTableName()
    {
        return $this->getRelatedModel()->getTable();
    }

    /**
     * Get the related model.
     *
     * @return null|Model
     */
    public function getRelatedModel()
    {
        return (new GridModel())->setTable($this->getPivotTableName());
    }

    /**
     * Get the rules.
     *
     * @return array
     */
    public function getRules()
    {
        $rules = parent::getRules();

        if ($min = array_get($this->getConfig(), 'min')) {
            $rules[] = 'min:' . $min;
        }

        if ($max = array_get($this->getConfig(), 'max')) {
            $rules[] = 'max:' . $max;
        }

        return $rules;
    }

    /**
     * Return the input value.
     *
     * @param null $default
     * @return null|MultipleFormBuilder
     */
    public function getInputValue($default = null)
    {
        return $this->dispatch(new GetMultiformFromPost($this));
    }

    /**
     * Return if any posted input is present.
     *
     * @return boolean
     */
    public function hasPostedInput()
    {
        return true;
    }

    /**
     * Get the validation value.
     *
     * @param null $default
     * @return array
     */
    public function getValidationValue($default = null)
    {
        if (!$forms = $this->getInputValue($default)) {
            return [];
        }

        return $forms->getForms()->map(
            function ($builder) {

                /* @var FormBuilder $builder */
                return $builder->getFormEntryId();
            }
        )->all();
    }

    /**
     * Get the value to index.
     *
     * @return string
     */
    public function getSearchableValue()
    {
        return json_encode(
            array_filter(
                array_map(
                    function (GridModel $row) {

                        if (!$entry = $row->getEntry()) {
                            return null;
                        }

                        return $entry->toSearchableArray();
                    },
                    $this->entry->{$this->getField()}->all()
                )
            )
        );
    }

    /**
     * Return a form builder instance.
     *
     * @param FieldInterface $field
     * @param StreamInterface $stream
     * @param null $instance
     * @return FormBuilder
     */
    public function form(FieldInterface $field, StreamInterface $stream, $instance = null)
    {
        /* @var EntryInterface $model */
        $model = $stream->getEntryModel();

        /* @var FormBuilder $builder */
        $builder = $model->newGridFieldTypeFormBuilder()
            ->setModel($model)
            ->setOption('success_message', false)
            ->setOption('grid_instance', $instance)
            ->setOption('grid_field', $field->getId())
            ->setOption('grid_title', $stream->getName())
            ->setOption('grid_prefix', $this->getFieldName())
            ->setOption('prefix', $this->getFieldName() . '_' . $instance . '_');

        $builder
            ->setOption('form_view', $builder->getOption('form_view', 'anomaly.field_type.grid::form'))
            ->setOption('wrapper_view', $builder->getOption('wrapper_view', 'anomaly.field_type.grid::wrapper'));

        return $builder;
    }

    /**
     * Return an array of entry forms.
     *
     * @return array
     */
    public function forms()
    {
        if (!$forms = $this->dispatch(new GetMultiformFromValue($this))) {
            return [];
        }

        return array_map(
            function (FormBuilder $form) {
                return $form
                    ->make()
                    ->getForm();
            },
            $forms->getForms()->all()
        );
    }

    /**
     * Handle saving the form data ourselves.
     *
     * @param FormBuilder $builder
     */
    public function handle(FormBuilder $builder)
    {
        $entry = $builder->getFormEntry();

        /**
         * If we don't have any forms then
         * there isn't much we can do.
         */
        if (!$forms = $this->getInputValue()) {

            $entry->{$this->getField()} = null;

            return;
        }

        /**
         * Skip self handling field types since they
         * will handle themselves later. Otherwise
         * this causes some mad recursion issues.
         *
         * @var FormBuilder $form
         */
        foreach ($forms->getForms() as $form) {

            $skips = $form
                ->getFormFields()
                ->selfHandling()
                ->fieldSlugs();

            $form->setSkips($skips);
        }

        /*
         * Handle the post action
         * for all the child forms.
         */
        $forms->handle();

        // See the accessor for how IDs are handled.
        $entry->{$this->getField()} = $forms->getForms()->map(
            function ($builder) {

                /* @var FormBuilder $builder */
                return [
                    'related_id' => $this->entry->getId(),
                    'entry_id'   => $builder->getFormEntryId(),
                    'entry_type' => get_class($builder->getFormEntry()),
                ];
            }
        )->all();
    }

    /**
     * Fired just before version comparison.
     *
     * @param EntryInterface|EloquentModel $entry
     */
    public function onVersioning(EntryInterface $entry)
    {
        $entry
            ->unsetRelation(camel_case($this->getField()))
            ->load(camel_case($this->getField()));
    }

    /**
     * Fired just before version comparison.
     *
     * @param GridCollection $related
     * @return array
     */
    public function toArrayForComparison(GridCollection $related)
    {
        return $related->map(
            function (GridModel $model) {

                $array = array_diff_key(
                    $model->toArray(),
                    array_flip(
                        [
                            'id',
                            'sort_order',
                            'created_at',
                            'created_by_id',
                            'updated_at',
                            'updated_by_id',
                            'deleted_at',
                            'deleted_by_id',

                            'field',
                            'pivot',
                        ]
                    )
                );

                array_pull($array, 'entry.sort_order');
                array_pull($array, 'entry.created_at');
                array_pull($array, 'entry.created_by_id');
                array_pull($array, 'entry.updated_at');
                array_pull($array, 'entry.updated_by_id');

                return $array;
            }
        )->toArray();
    }
}
