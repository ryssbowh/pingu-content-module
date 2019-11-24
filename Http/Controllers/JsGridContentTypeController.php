<?php

namespace Pingu\Content\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pingu\Content\Entities\ContentField;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Entities\Field;
use Pingu\Content\Forms\ContentFieldForm;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Fields\Model;
use Pingu\Forms\Fields\Serie;
use Pingu\Forms\Form;
use Pingu\Jsgrid\Http\Controllers\JsGridModelController;

class JsGridContentTypeController extends JsGridModelController
{
    /**
     * @inheritDoc
     */
    public function getModel()
    {
        return ContentType::class;
    }

    /**
     * @inheritDoc
     */
    public function index(Request $request)
    {
        $options['jsgrid'] = $this->buildJsGridView($request);
        $options['title'] = str_plural(ContentType::friendlyName());
        $options['canSeeAddLink'] = Auth::user()->can('add content types');
        $options['addLink'] = ContentType::getUri('create', adminPrefix());
        
        return view('pages.listModel-jsGrid', $options);
    }

    /**
     * @inheritDoc
     */
    protected function canClick()
    {
        return Auth::user()->can('edit content types');
    }

    /**
     * @inheritDoc
     */
    protected function canDelete()
    {
        return Auth::user()->can('delete content types');
    }

    /**
     * @inheritDoc
     */
    protected function canEdit()
    {
        return $this->canClick();
    }
}
