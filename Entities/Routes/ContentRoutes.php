<?php

namespace Pingu\Content\Entities\Routes;

use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Entity\Support\BaseEntityRoutes;

class ContentRoutes extends BaseEntityRoutes
{
    protected function names(): array
    {
        return [
            'admin.index' => 'content.admin.content'
        ];
    }
}