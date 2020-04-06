<?php 

namespace Pingu\Content\Renderers;

use Pingu\Block\Support\BlockRenderer;
use Pingu\Content\Blocks\ContentBlock;

class ContentBlockRenderer extends BlockRenderer
{
    public function __construct(ContentBlock $block)
    {
        parent::__construct($block);
    }

    public function rendererType(): string
    {
        return 'content';
    }
}