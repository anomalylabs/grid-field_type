<?php namespace Anomaly\GridFieldType\Grid;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class GridRelation
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GridRelation extends HasMany
{

    /**
     * The related parent.
     *
     * @var EntryInterface
     */
    protected $parent;

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->get();
    }

    /**
     * Get the relation results.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($columns = ['*'])
    {
        /* @var GridCollection $collection */
        $collection = parent::get($columns);

        $collection
            ->setModel($this->parent)
            ->setField($this->getForeignKeyName());

        return $collection;
    }

    /**
     * Create a new instance of the related model.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
        /**
         * Mutate the 'entry' attribute since
         * that is what we're used to.
         *
         * @var EntryInterface $entry
         */
        if (array_get($attributes, 'entry') instanceof EntryInterface) {

            $entry = array_pull($attributes, 'entry');

            array_set($attributes, 'entry_id', $entry->getId());
            array_set($attributes, 'entry_type', get_class($entry));
        }

        // Here we will set the raw attributes to avoid hitting the "fill" method so
        // that we do not have to worry about a mass accessor rules blocking sets
        // on the models. Otherwise, some of these attributes will not get set.
        $instance = $this->related->newInstance($attributes);

        $instance->setTable($this->related->getTable());

        $instance->setAttribute($this->getForeignKeyName(), $this->getParentKey());

        $instance->save();

        return $instance;
    }

}
