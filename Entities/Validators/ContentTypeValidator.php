<?php

namespace Pingu\Content\Entities\Validators;

use Pingu\Field\Support\FieldValidator\BaseFieldsValidator;

class ContentTypeValidator extends BaseFieldsValidator
{
    /**
     * @inheritDoc
     */
    protected function rules(bool $updating): array
    {
        return [
            'name' => 'required|string',
            'description' => 'sometimes|string',
            'machineName' => 'required|unique:content_types,machineName,'.$this->object->id.',id'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'machineName.required' => 'Machine Name is required',
            'machineName.unique' => 'Machine name already exists'
        ];
    }
}