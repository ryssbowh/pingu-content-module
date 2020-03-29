<?php

namespace Pingu\Content\Entities\Fields;

use Pingu\Content\Entities\ContentType;
use Pingu\Entity\Support\FieldRepository\BundledEntityFieldRepository;
use Pingu\Field\BaseFields\Boolean;
use Pingu\Field\BaseFields\Model;
use Pingu\Field\BaseFields\Text;

class ContentFields extends BundledEntityFieldRepository
{
    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            new Text('title', [
                'maxLength' => 255, 
                'required' => true
            ]),
            new Text('slug', [
                'maxLength' => 255,
                'required' => true
            ]),
            new Model('content_type', [
                'model' => ContentType::class,
                'textField' => 'name',
                'disabled' => true
            ]),
            new Boolean('published')
        ];
    }
}