<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pingu\Content\Entities\Content;
use Pingu\Core\Http\Controllers\AjaxModelController;

class AjaxContentController extends AjaxModelController
{
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Content::class;
    }
}
