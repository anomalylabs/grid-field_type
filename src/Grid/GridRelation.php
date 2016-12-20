<?php namespace Anomaly\GridFieldType\Grid;

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
     * Create a new instance of the related model.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes)
    {
        // Here we will set the raw attributes to avoid hitting the "fill" method so
        // that we do not have to worry about a mass accessor rules blocking sets
        // on the models. Otherwise, some of these attributes will not get set.
        $instance = $this->related->newInstance($attributes);

        $instance->setTable($this->related->getTable());

        $instance->setAttribute($this->getPlainForeignKey(), $this->getParentKey());

        $instance->save();

        return $instance;
    }
}
