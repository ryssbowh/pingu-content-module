<?php

namespace Pingu\Content\Http\Controllers;

use Pingu\Entity\Entities\Entity;
use Pingu\Entity\Http\Controllers\AdminEntityController;
use Pingu\Forms\Support\Form;

class ContentTypeAdminController extends AdminEntityController
{
    /**
     * @inheritDoc
     */
    protected function afterEditFormCreated(Form $form, Entity $entity)
    {
        $form->getElement('machineName')->option('disabled', true);
    }

    /**
     * @inheritDoc
     */
    protected function afterCreateFormCreated(Form $form, Entity $entity)
    {
        $field = $form->getElement('machineName');
        $field->classes->add('js-dashify');
        $field->attribute('data-dashifyfrom', 'name');
    }
}
