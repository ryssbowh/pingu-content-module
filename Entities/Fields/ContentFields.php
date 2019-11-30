<?php

namespace Pingu\Content\Entities\Fields;

use Pingu\Field\BaseFields\Text;
use Pingu\Field\Support\FieldRepository\BundledEntityFieldRepository;

class ContentFields extends BundledEntityFieldRepository
{
    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
        ];
    }
}