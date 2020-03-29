<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Entity\Support\Entity;
use Pingu\Entity\Http\Controllers\AdminEntityController;
use Pingu\Forms\Support\Form;

class ContentAdminController extends AdminEntityController
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
        $entity->saveWithRelations($validated);
    }

    /**
     * @inheritDoc
     */
    protected function addVariablesToIndexView(array &$with)
    {
        $with['createUrl'] = Content::routeSlug().'/create';
    }

    /**
     * @inheritDoc
     */
    protected function afterCreateFormCreated(Form $form, Entity $entity)
    {
        $field = $form->getElement('slug');
        $field->classes->add('js-dashify');
        $field->attribute('data-dashifyfrom', 'title');
    }
}
