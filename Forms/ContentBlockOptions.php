<?php

namespace Pingu\Content\Forms;

use Pingu\Block\Entities\Block;
use Pingu\Content\Blocks\ContentBlock;
use Pingu\Forms\Support\Fields\Select;
use Pingu\Forms\Support\Fields\Submit;
use Pingu\Forms\Support\Form;

class ContentBlockOptions extends Form
{
    /**
     * @var ContentBlock
     */
    protected $block;
    /**
     * @var Block
     */
    protected $model;

    /**
     * Bring variables in your form through the constructor :
     */
    public function __construct(ContentBlock $block, Block $model = null)
    {
        $this->block = $block;
        $this->model = $model;
        parent::__construct();
    }

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
        $fields = [
            new Select(
                'id',
                [
                    'label' => $this->block->contentType()->name,
                    'items' => $this->getContents()
                ]
            ),
            new Submit()
        ];
        return $fields;
    }

    public function afterBuilt()
    {
        if ($this->model) {
            $data = $this->model->data;
            foreach ($this->getElements() as $element) {
                if (isset($data[$element->getName()])) {
                    $element->setValue($data[$element->getName()]);
                }
            }
        }
    }

    /**
     * Method for this form, POST GET DELETE PATCH and PUT are valid
     * 
     * @return string
     */
    public function method(): string
    {
        return $this->model ? 'PUT' : 'POST';
    }

    /**
     * Url for this form, valid values are
     * ['url' => '/foo.bar']
     * ['route' => 'login']
     * ['action' => 'MyController@action']
     * 
     * @return array
     * @see https://github.com/LaravelCollective/docs/blob/5.6/html.md
     */
    public function action(): array
    {
        if ($this->model) {
            return ['url' => Block::uris()->make('update', $this->model)];
        } else {
            return ['url' => Block::uris()->make('store', $this->block->fullMachineName())];
        }
    }

    /**
     * Name for this form, ideally it would be application unique, 
     * best to prefix it with the name of the module it's for.
     * only alphanumeric and hyphens
     * 
     * @return string
     */
    public function name(): string
    {
        return 'content-block-options';
    }

    /**
     * Various options that you can access in your templates/events
     
     * @return array
     */
    public function options(): array
    {
        return [
            'title' => 'Add a block '.$this->block->name()
        ];
    }
}