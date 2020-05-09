<?php 

namespace Pingu\Content\Context;

use Pingu\Core\Http\Contexts\UpdateContext;
use Pingu\Field\Contracts\HasFieldsContract;

class UpdateContentTypeContext extends UpdateContext
{
    /**
     * @inheritDoc
     */
    public function getValidationRules(HasFieldsContract $model): array
    {
        return $model->fieldRepository()->validationRules()->except('machineName')->toArray();
    }
}