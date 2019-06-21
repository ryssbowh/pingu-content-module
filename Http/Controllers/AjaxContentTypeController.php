<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pingu\Content\Entities\ContentType;
use Pingu\Core\Http\Controllers\AjaxModelController;

class AjaxContentTypeController extends AjaxModelController
{
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return ContentType::class;
    }
}
