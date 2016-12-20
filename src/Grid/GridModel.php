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
}
