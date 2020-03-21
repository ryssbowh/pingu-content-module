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
            'field_slug.*' => 'string|required|unique_field:'.get_class($this->object).','.$this->object->id
        ];
    }

    /**
     * @inheritDoc
     */
    protected function messages(): array
    {
        return [
            'field_slug.*.unique_field' => 'Slug :value already exists'
        ];
    }
}