<?php namespace Anomaly\GridFieldType\Validation;

use Anomaly\GridFieldType\Command\GetMultiformFromPost;
use Anomaly\GridFieldType\GridFieldType;
use Anomaly\Streams\Platform\Ui\Form\Multiple\MultipleFormBuilder;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class ValidateGrid
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class ValidateGrid
{

    use DispatchesJobs;

    /**
     * Handle the command.
     *
     * @param GridFieldType $fieldType
     * @return bool
     */
    public function handle(GridFieldType $fieldType)
    {
        /* @var MultipleFormBuilder $forms */
        if (!$forms = $this->dispatch(new GetMultiformFromPost($fieldType))) {
            return true;
        }

        $forms
            ->build()
            ->validate();

        if (!$forms->getFormErrors()->isEmpty()) {
            return false;
        }

        return true;
    }
}
