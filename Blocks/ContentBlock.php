<?php 

namespace Pingu\Content\Blocks;

use Pingu\Block\Contracts\BlockContract;
use Pingu\Block\Contracts\BlockProviderContract;
use Pingu\Block\Entities\Block;
use Pingu\Block\Support\Block as BlockTrait;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Forms\ContentBlockOptions;
use Pingu\Forms\Support\Form;

class ContentBlock implements BlockContract
{
    use BlockTrait;

    protected $contentType;
    protected $model;
    protected $content;

    /**
     * @inheritDoc
     */
    public function __construct(?Block $model = null)
    {
        $this->model = $model;
        if ($model) {
            $this->contentType = ContentType::find($model->getData('contentType'));
            $this->content = Content::find($model->getData('id'));
        }
    }

    public function getContent()
    {
        return $this->content;
    }

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
     * @return ContentType
     */
    public function contentType(): ContentType
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
    public function createOptionsForm(): Form
    {
        return new ContentBlockOptions($this);
    }

    /**
     * @inheritDoc
     */
    public function editOptionsForm(Block $block): Form
    {
        return new ContentBlockOptions($this, $block);
    }

    /**
     * @inheritDoc
     */
    public function getOptionsValidationRules(): array
    {
        return [
            'id' => 'required|exists:contents'
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

    public function getViewData(): array
    {
        return [
            'contentType' => $this->contentType,
            'content' => $this->content
        ];
    }
}