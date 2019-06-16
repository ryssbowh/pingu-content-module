<?php

namespace Pingu\Content\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pingu\Content\Entities\ContentField;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Entities\Field;
use Pingu\Content\Entities\Fields\FieldText;
use Pingu\Content\Forms\ContentFieldForm;
use Pingu\Core\Contracts\Controllers\HandlesModelContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\Controllers\HandlesModel;
use Pingu\Forms\Fields\Model;
use Pingu\Forms\Fields\Serie;
use Pingu\Forms\Form;
use Pingu\Jsgrid\Contracts\Controllers\JsGridContract;
use Pingu\Jsgrid\Traits\Controllers\JsGrid;

class ContentTypeController extends BaseController implements HandlesModelContract
{
    use HandlesModel;

    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return ContentType::class;
    }

    /**
     * List all fields for a content type
     * @return Response
     */
    public function listFields(Request $request, ContentType $type)
    {
        \ContextualLinks::addModelLinks($type);
        $items = [];
        foreach(\Content::getRegisteredContentFields() as $name => $class){
            $items[$name] = $class::friendlyName();
        }

        $url = ContentType::transformAdminUri('addField', [$type], true);
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
        return redirect(ContentType::transformAdminUri('listFields', [$model], true));
    }
}
