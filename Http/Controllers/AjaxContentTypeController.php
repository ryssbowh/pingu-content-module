<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pingu\Content\Entities\ContentType;
use Pingu\Core\Contracts\Controllers\HandlesAjaxModelContract;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\Controllers\HandlesAjaxModel;

class AjaxContentTypeController extends BaseController implements HandlesAjaxModelContract
{
    use HandlesAjaxModel;
    
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return ContentType::class;
    }
}
