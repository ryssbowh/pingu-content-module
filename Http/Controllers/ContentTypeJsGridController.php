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

class ContentTypeJsGridController extends BaseController implements HandlesModelContract, JsGridContract
{
    use HandlesModel, JsGrid;

    /**
     * @inheritDoc
     */
    public function getModel(): string
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
        $options['addLink'] = ContentType::getAdminUri('create', true);
        
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
