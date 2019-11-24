<?php

namespace Pingu\Content\Entities\Fields;

use Pingu\Field\BaseFields\Text;
use Pingu\Field\Support\FieldRepository\BaseFieldRepository;

class ContentFields extends BaseFieldRepository
{
    protected function fields(): array
    {
        return [
            new Text(
                'slug',
                [
                    'required' => true
                ]
            )
        ];
    }
}