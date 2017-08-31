<?php namespace Anomaly\GridFieldType\Support\Config;

use Anomaly\CheckboxesFieldType\CheckboxesFieldType;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;

class StreamsOptions
{

    /**
     * Handle the select options.
     *
     * @param      CheckboxesFieldType        $fieldType  The field type
     * @param      StreamRepositoryInterface  $streams    The streams
     */
    public function handle(
        CheckboxesFieldType $fieldType,
        StreamRepositoryInterface $streams
    )
    {
        $options = [];

        /* @var StreamInterface $stream */
        foreach ($streams->findAllByNamespace('grid') as $stream) {
            array_set(
                $options,
                $stream->getEntryModelName(),
                $stream->getName()
            );
        }

        ksort($options);

        $fieldType->setOptions($options);
    }
}
