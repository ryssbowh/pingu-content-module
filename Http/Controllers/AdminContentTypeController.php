<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Http\Response;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Forms\ContentFieldForm;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\AdminModelController;
use Pingu\Forms\Form;

class AdminContentTypeController extends AdminModelController
{
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return ContentType::class;
    }

    /**
     * List all fields for a content type
     * 
     * @return Response
     */
    public function listFields(ContentType $type)
    {
        \ContextualLinks::addModelLinks($type);
        $items = [];
        foreach(\Content::getRegisteredContentFields() as $name => $class){
            $items[$name] = $class::friendlyName();
        }

        $url = ContentType::transformUri('addField', [$type], config('core.adminPrefix'));
        $form = new ContentFieldForm($url, $items);

        return view('content::listFields')->with([
            'fields' => $type->fields,
            'contentType' => $type,
            'form' => $form
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function onSuccessfullStore(BaseModel $model)
    {
        return redirect(ContentType::transformUri('listFields', [$model], config('core.adminPrefix')));
    }
}
