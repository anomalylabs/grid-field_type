<?php namespace Anomaly\GridFieldType\Support\Config;

use Anomaly\CheckboxesFieldType\CheckboxesFieldType;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;

/**
 * Class RelatedHandler
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class RelatedHandler
{

    /**
     * Handle the options.
     *
     * @param CheckboxesFieldType $fieldType
     * @param StreamRepositoryInterface $streams
     */
    public function handle(CheckboxesFieldType $fieldType, StreamRepositoryInterface $streams)
    {
        $options = [];

        /* @var StreamInterface as $stream */
        foreach ($streams->findAllByNamespace('grid') as $stream) {
            $options[$stream->getEntryModelName()] = $stream->getName();
        }

        ksort($options);

        $fieldType->setOptions($options);
    }
}
