<?php

namespace Pingu\Content\Entities\Validators;

use Pingu\Field\Support\FieldValidator\BaseFieldsValidator;

class ContentValidator extends BaseFieldsValidator
{
    /**
     * @inheritDoc
     */
    protected function rules(): array
    {
        return [
            //'slug' => 'string|required|unique:contents,slug,'.$this->object->id
        ];
    }

    /**
     * @inheritDoc
     */
    protected function messages(): array
    {
        return [

        ];
    }
}