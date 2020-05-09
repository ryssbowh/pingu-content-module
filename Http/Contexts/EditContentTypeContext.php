<?php 

namespace Pingu\Content\Context;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Contexts\EditContext;

class EditContentTypeContext extends EditContext
{
    /**
     * @inheritDoc
     */
    public function getFields(): Collection
    {
        $fields = $this->model->fieldRepository()->all();
        $fields->get('machineName')->option('disabled', true);
        return $fields;
    }
}