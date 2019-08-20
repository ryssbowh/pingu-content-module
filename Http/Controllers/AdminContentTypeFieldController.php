<?php

namespace Pingu\Content\Http\Controllers;

use Pingu\Content\Entities\ContentType;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\AdminModelController;

class AdminContentTypeFieldController extends AdminModelController
{   
    use ContentTypeFieldController;

    /**
     * @inheritDoc
     */
    protected function onStoreSuccess(BaseModel $field)
    {
        return redirect(ContentType::makeUri('listFields', [$this->contentType], adminPrefix()));
    }

    /**
     * @inheritDoc
     */
    protected function afterStoreSuccess(BaseModel $field)
    {
        \Notify::success($field::friendlyName().' field '.$field->field->name.' has been saved');
    }

    /**
     * @inheritDoc
     */
    protected function addVariablesToCreateView(array &$with)
    {
        $with['title'] = 'Add a '.$this->fieldType::friendlyname().' field';
    }

    /**
     * @inheritDoc
     */
    protected function addVariablesToEditView(array &$with, BaseModel $model)
    {
        $with['title'] = 'Edit field '.$model->name;
    }

    /**
     * @inheritDoc
     */
    protected function onUpdateSuccess(BaseModel $field)
    {
        return redirect($field->field->content_type::makeUri('listFields', [$field->field->content_type], adminPrefix()));
    }

    /**
     * @inheritDoc
     */
    protected function onDeleteSuccess(BaseModel $field)
    {
        return redirect($field->content_type::makeUri('listFields', [$field->content_type], adminPrefix()));
    }
}
