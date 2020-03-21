<?php

namespace Pingu\Content\Forms;

use Pingu\Block\Entities\Block;
use Pingu\Block\Forms\BlockOptionsForm;
use Pingu\Content\Blocks\ContentBlock;
use Pingu\Forms\Support\Fields\Select;
use Pingu\Forms\Support\Fields\Submit;
use Pingu\Forms\Support\Form;

class ContentBlockOptions extends BlockOptionsForm
{
    /**
     * Get all the contents for a content type as array
     * indexed by ids.
     * 
     * @return array
     */
    protected function getContents()
    {
        $contents = $this->block->contentType()->contents->keyBy('id');
        $out = [];
        foreach ($contents as $content) {
            $out[$content->id] = $content->field_title[0];
        }
        return $out;
    }

    /**
     * Fields definitions for this form, classes used here
     * must extend Pingu\Forms\Support\Field
     * 
     * @return array
     */
    public function elements(): array
    {
        $fields = $this->model->fields()->toFormElements($this->model, $this->updating);
        $fields = array_merge(
            $fields, [
            new Select(
                'id',
                [
                    'label' => $this->block->contentType()->name,
                    'items' => $this->getContents()
                ]
            ),
            new Submit()
            ]
        );
        return $fields;
    }
}