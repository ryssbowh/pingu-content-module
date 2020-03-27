<?php

namespace Pingu\Content\Entities\Routes;

use Pingu\Entity\Support\Routes\BundledEntityRoutes;

class ContentRoutes extends BundledEntityRoutes
{
    protected function names(): array
    {
        return [
            'admin.index' => 'content.admin.content'
        ];
    }
}