<?php

namespace Pingu\Content\Http\Controllers;

use Pingu\Content\Entities\ContentType;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\AjaxModelController;

class AjaxContentTypeFieldController extends AjaxModelController
{   
    use ContentTypeFieldController;
}
