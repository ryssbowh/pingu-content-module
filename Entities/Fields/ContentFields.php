<?php

namespace Pingu\Content\Entities\Fields;

use Illuminate\Support\Collection;
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
                'required' => true,
                'dashifyFrom' => 'title'
            ]),
            new Model('content_type', [
                'model' => ContentType::class,
                'textField' => 'name',
                'disabled' => true
            ]),
            new Boolean('published')
        ];
    }

    /**
     * @inheritDoc
     */
    protected function rules(): array
    {
        return [
            'slug' => 'string|required|alpha_dash|unique:contents,slug,'.$this->object->id,
            'title' => 'string|required'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function messages(): array
    {
        return [];
    }
}