<?php namespace Anomaly\GridFieldType\Grid;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Robbo\Presenter\PresentableInterface;

/**
 * Class GridModel
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GridModel extends Model implements PresentableInterface
{

    /**
     * No dates.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Eager load these relations.
     *
     * @var array
     */
    protected $with = [
        'entry',
    ];

    /**
     * Fillable properties.
     *
     * @var array
     */
    protected $fillable = [
        'entry_id',
        'entry_type',
        'related_id',
        'sort_order',
    ];

    /**
     * Return the type of grid.
     *
     * @return string
     */
    public function type()
    {
        return $this
            ->getEntry()
            ->getStreamSlug();
    }

    /**
     * Return the related entry.
     *
     * @return EntryInterface
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Return the entry relation.
     *
     * @return MorphTo
     */
    public function entry()
    {
        return $this->morphTo('entry');
    }

    /**
     * Return a created presenter.
     *
     * @return GridPresenter
     */
    public function getPresenter()
    {
        return new GridPresenter($this);
    }

    /**
     * Return a new collection instance.
     *
     * @param array $models
     * @return GridCollection
     */
    public function newCollection(array $models = [])
    {
        return new GridCollection($models);
    }

    /**
     * Define a polymorphic, inverse one-to-one or many relationship.
     *
     * @param  string $name
     * @param  string $type
     * @param  string $id
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function morphTo($name = null, $type = null, $id = null, $ownerKey = null)
    {

        /**
         * Check that the grid relation still
         * exists. If it does NOT then we send
         * a bogus relation back instead.
         */
        if (!class_exists($this->entry_type)) {
            return new MorphTo(
                $this->newQuery(), $this, -1, null, $type, $name
            );
        }

        return parent::morphTo($name, $type, $id, $ownerKey);
    }
}
