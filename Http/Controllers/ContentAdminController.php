<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Entity\Http\Controllers\EntityCrudContextController;
use Pingu\Entity\Support\Entity;
use Pingu\Forms\Support\Form;

class ContentAdminController extends EntityCrudContextController
{
    public function createIndex()
    {
        $types = [];
        foreach (ContentType::all() as $type) {
            if (\Gate::check('create', Content::class, $type->toBundle())) {
                $types[] = $type;
            }
        }
        return view('pages.content.create')->with(
            [
            'types' => $types,
            'content' => Content::class
            ]
        );
    }

    /**
     * @inheritDoc
     */
    protected function performStore(Entity $entity, array $validated)
    {
        $contentType = $this->routeParameter('bundle')->getEntity();
        $entity->content_type()->associate($contentType);
        $entity->saveFields($validated);
    }

    /**
     * @inheritDoc
     */
    protected function addVariablesToIndexView(array &$with)
    {
        $with['createUrl'] = Content::routeSlug().'/create';
    }
}
