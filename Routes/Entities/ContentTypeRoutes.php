<?php

namespace Pingu\Content\Routes\Entities;

use Pingu\Entity\Support\BaseEntityRoutes;

class ContentTypeRoutes extends BaseEntityRoutes
{
    protected function routes(): array
    {
        return [
            'admin' => [
                'index', 'view', 'create', 'store', 'edit', 'update', 'patch', 'confirmDelete', 'delete'
            ],
            'ajax' => [
                'index', 'view', 'create', 'store', 'edit', 'update', 'patch', 'delete'
            ]
        ];
    }

    protected function routeMiddlewares(): array
    {
        return [
            'index' => 'can:view content types',
            'create' => 'can:add content types',
            'store' => 'can:add content types',
            'edit' => 'can:edit content types',
            'update' => 'can:edit content types'
        ];
    }

    protected function routeNames(): array
    {
        return [
            'admin.index' => 'content.admin.contentTypes',
            'admin.create' => 'content.admin.contentTypes.create'
        ];
    }
}