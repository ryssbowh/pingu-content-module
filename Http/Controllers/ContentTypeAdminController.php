<?php

namespace Pingu\Content\Http\Controllers;

use Pingu\Entity\Support\Entity;
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
}
