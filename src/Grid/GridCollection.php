<?php

namespace Anomaly\GridFieldType\Grid;

use Anomaly\Streams\Platform\Addon\Plugin\PluginCriteria;
use Anomaly\Streams\Platform\Entry\EntryModel;
use Anomaly\Streams\Platform\Model\EloquentCollection;
use Anomaly\Streams\Platform\Model\EloquentModel;
use Anomaly\Streams\Platform\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GridCollection
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GridCollection extends EloquentCollection
{

    /**
     * The model accessing the collection.
     *
     * @var null|EloquentModel|EntryModel|Model
     */
    protected $model = null;

    /**
     * The grid field slug.
     *
     * @var null|string
     */
    protected $field = null;

    /**
     * Return if the grid has a
     * type or default to key.
     *
     * @param mixed $key
     * @return bool
     */
    public function has($key)
    {
        $item = $this->first(
            function ($grid) use ($key) {

                /* @var GridModel $grid */
                return str_is($key, $grid->type());
            }
        );

        if ($item) {
            return true;
        }

        return parent::has($key);
    }

    /**
     * Render the grid views.
     *
     * @return string
     */
    public function views()
    {
        return (new PluginCriteria(
            'render',
            function (Collection $options) {

                $name = $options->get('name', 'grid');
                $path = trim($options->get('path', 'theme::grids'), '/') . '/';

                return $this->map(
                    function ($grid) use ($path, $name, $options) {

                        /* @var GridModel $grid */
                        return view(
                            $path . $grid->type(),
                            array_merge([
                                $name     => $grid,
                                'options' => $options,
                            ], $options->payload ?: [])
                        )->render();
                    }
                )->implode("\n");
            }
        ))
            ->setModel($this->getModel())
            ->setCachePrefix('anomaly.field_type.grid::grids.views.' . $this->getField());
    }

    /**
     * Get the model.
     *
     * @return EntryModel|EloquentModel|Model|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the model.
     *
     * @param $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the field.
     *
     * @return null|string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set the field.
     *
     * @param $field
     * @return $this
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }
}
