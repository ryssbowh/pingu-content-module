<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Events\ContentValidatorCreated;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Forms\Fields\Boolean;
use Pingu\Forms\Fields\Text;
use Pingu\Forms\Form;
use Pingu\Jsgrid\Http\Controllers\JsGridController;

class JsGridContentController extends JsGridController
{
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Content::class;
    }

    /**
     * @inheritDoc
     */
    protected function canClick()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function canDelete()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function canEdit()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function index(Request $request)
    {
        $options['jsgrid'] = $this->buildJsGridView($request);
        $options['title'] = 'Content';
        $options['canSeeAddLink'] = false;
        $options['addLink'] = '';
        
        return view('pages.listModel-jsGrid', $options);
    }
}
