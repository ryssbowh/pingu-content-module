<?php

namespace Pingu\Content\Entities\Validators;

use Pingu\Field\Support\FieldValidator\BaseFieldsValidator;

class ContentValidator extends BaseFieldsValidator
{
    /**
     * @inheritDoc
     */
    protected function rules(bool $updating): array
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