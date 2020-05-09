<?php 

namespace Pingu\Content\Blocks;

use Pingu\Block\Contracts\BlockContract;
use Pingu\Block\Contracts\BlockProviderContract;
use Pingu\Block\Entities\Block;
use Pingu\Block\Support\Block as BlockTrait;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Forms\ContentBlockOptions;
use Pingu\Entity\Entities\ViewMode;
use Pingu\Forms\Support\Fields\Select;
use Pingu\Forms\Support\Form;

class ContentBlock implements BlockContract
{
    use BlockTrait {
        toArray as traitToArray;
    }

    /**
     * @var ContentType
     */
    protected $contentType;

    /**
     * @var Block
     */
    protected $model;

    /**
     * @var Content
     */
    protected $content;

    /**
     * @var ViewMode
     */
    protected $viewMode;

    /**
     * @inheritDoc
     */
    public function __construct(?Block $model = null)
    {
        $this->model = $model;
        if ($model) {
            $this->contentType = ContentType::find($model->getData('contentType'));
            $this->content = Content::find($this->getData('id'));
            $this->viewMode = \ViewMode::get($this->getData('viewMode'));
        }
    }

    /**
     * Get content associated with this block
     * 
     * @return Content
     */
    public function getContent(): Content
    {
        return $this->content;
    }

    /**
     * @inheritDoc
     */
    public function systemView(): string
    {
        return 'content@content-block';
    }

    /**
     * Set the content type for this block
     * 
     * @param ContentType $type
     */
    public function setContentType(ContentType $type)
    {
        $this->contentType = $type;
        return $this;
    }

    /**
     * Get the content type
     * 
     * @return ?ContentType
     */
    public function contentType(): ?ContentType
    {
        return $this->contentType;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultData(): array
    {
        return [
            'contentType' => $this->contentType->id
        ];
    }

    /**
     * @inheritDoc
     */
    public function hasOptions(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getOptionsFormFields(): array
    {
        return [
            new Select(
                'id',
                [
                    'label' => $this->contentType()->name,
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
        ];
    }

    /**
     * @inheritDoc
     */
    public function getOptionsValidationRules(): array
    {
        return [
            'id' => 'required|exists:contents',
            'viewMode' => 'required|integer|exists:view_modes,id',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getOptionsValidationMessages(): array
    {
        return [
            'id.required' => 'You must choose a content',
            'id.exists' => 'This content doesn\'t exist'
        ];
    }

    /**
     * View mode getter
     * 
     * @return ?ViewMode
     */
    public function getViewMode(): ?ViewMode
    {
        return $this->viewMode;
    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return $this->content->content_type->name.' : '.$this->content->title;
    }

    /**
     * @inheritDoc
     */
    public function section(): string
    {
        return 'Content';
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return $this->contentType->name;
    }

    /**
     * @inheritDoc
     */
    public function machineName(): string
    {
        return $this->contentType->machineName;
    }

    /**
     * @inheritDoc
     */
    public function provider(): string
    {
        return 'content';
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return array_merge([
            'contentType' => $this->contentType(),
            'content' => $this->content,
            'viewMode' => $this->getViewMode()
        ], $this->traitToArray());
    }

    /**
     * @inheritDoc
     */
    public function getViewData(): array
    {
        return [
            'contentType' => $this->contentType(),
            'content' => $this->content,
            'viewMode' => $this->getViewMode(),
            'fields' => $this->content->bundle()->fieldDisplay()->buildForRendering($this->getViewMode(), $this->content)
        ];
    }

    /**
     * Get all view modes for the content type bundle
     * 
     * @return array
     */
    protected function getViewModes(): array
    {
        $bundle = $this->contentType()->toBundle();
        $out = [];
        foreach (\ViewMode::forObject($bundle) as $viewMode) {
            $out[$viewMode->id] = $viewMode->name;
        }
        return $out;
    }

    /**
     * Get all the contents for a content type as array
     * indexed by ids.
     * 
     * @return array
     */
    protected function getContents()
    {
        $contents = $this->contentType()->contents->keyBy('id');
        $out = [];
        foreach ($contents as $content) {
            $out[$content->id] = $content->title;
        }
        return $out;
    }
}