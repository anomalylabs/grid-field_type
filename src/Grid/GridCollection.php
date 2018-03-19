<?php namespace Anomaly\GridFieldType\Grid;

use Anomaly\Streams\Platform\Addon\Plugin\PluginCriteria;
use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Model\EloquentCollection;
use Anomaly\Streams\Platform\Support\Collection;

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
     * The parent entry object.
     *
     * @var null|EntryInterface
     */
    protected $entry = null;

    /**
     * The identifying key.
     *
     * @var null
     */
    protected $key = null;

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
        return new PluginCriteria(
            'render',
            function (Collection $options) {

                $name = $options->get('name', 'grid');
                $path = trim($options->get('path', 'theme::grids'), '/') . '/';

                return $this->entry->cache(
                    $this->getKey(),
                    $options->get('cache', null),
                    function () use ($path, $name, $options) {
                        return implode(
                            $this->map(
                                function ($grid) use ($path, $name, $options) {

                                    /* @var GridModel $grid */
                                    return view(
                                        $path . $grid->type(),
                                        [
                                            $name     => $grid,
                                            'options' => $options,
                                        ]
                                    )->render();
                                }
                            )->all(),
                            "\n"
                        );
                    }
                );
            }
        );
    }

    /**
     * Get the parent entry.
     *
     * @return EntryInterface|null
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Set the parent entry.
     *
     * @param EntryInterface $entry
     * @return $this
     */
    public function setEntry(EntryInterface $entry)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Get the identifying key.
     *
     * @return null
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the identifying key.
     *
     * @param $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

}
