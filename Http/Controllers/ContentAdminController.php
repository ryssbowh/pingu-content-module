<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Entity\Entities\Entity;
use Pingu\Entity\Http\Controllers\AdminEntityController;
use Pingu\Forms\Support\Form;

class ContentAdminController extends AdminEntityController
{
    public function createIndex()
    {
        $types = ContentType::all();
        $available = [];
        foreach ($types as $type) {
            if (\Auth::user()->can('create '.Str::plural($type->machineName))) {
                $available[] = $type;
            }
        }
        return view('content::create')->with(
            [
            'types' => $available,
            'content' => Content::class
            ]
        );
    }

    protected function afterCreateFormCreated(Form $form, Entity $entity)
    {
        $group = $form->getElement('field_slug');
        $group->first()->classes->add('js-dashify');
        $group->first()->attribute('data-dashifyfrom', 'field_title');
    }

    protected function performStore(Entity $entity, array $validated)
    {
        $contentType = $this->routeParameter('bundle')->getEntity();
        $entity->content_type()->associate($contentType);
        $entity->saveWithRelations($validated);
    }
}
