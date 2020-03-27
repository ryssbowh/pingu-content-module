<?php

namespace Pingu\Content\Entities\Routes;

use Pingu\Entity\Support\Routes\BaseEntityRoutes;

class ContentTypeRoutes extends BaseEntityRoutes
{
    protected function names(): array
    {
        return [
            'admin.index' => 'content.admin.contentTypes',
            'admin.create' => 'content.admin.contentTypes.create'
        ];
    }
}