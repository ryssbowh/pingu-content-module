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
            $out[$content->id] = $content->title;
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
            [
                new Select(
                    'id',
                    [
                        'label' => $this->block->contentType()->name,
                        'items' => $this->getContents()
                    ]
                ),
                new Select(
                    'viewMode',
                    [
                        'label' => 'View mode',
                        'items' => $this->getViewModes()
                    ]
                )
            ], $fields
        );
        $fields[] = new Submit('_submit');
        return $fields;
    }

    /**
     * Get all view modes for the content type bundle
     * 
     * @return array
     */
    protected function getViewModes(): array
    {
        $bundle = $this->block->contentType()->toBundle();
        $out = [];
        foreach (\ViewMode::forObject($bundle) as $viewMode) {
            $out[$viewMode->id] = $viewMode->name;
        }
        return $out;
    }
}