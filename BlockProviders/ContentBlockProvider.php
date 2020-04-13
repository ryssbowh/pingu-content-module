<?php

namespace Pingu\Content\BlockProviders;

use Pingu\Block\Contracts\BlockContract;
use Pingu\Block\Contracts\BlockProviderContract;
use Pingu\Block\Entities\Block;
use Pingu\Content\Blocks\ContentBlock;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Renderers\ContentBlockRenderer;
use Pingu\Forms\Support\Form;

class ContentBlockProvider implements BlockProviderContract
{
    /**
     * @inheritDoc
     */
    public static function machineName(): string
    {
        return 'content';
    }

    /**
     * @inheritDoc
     */
    public function load(Block $block): BlockContract
    {
        return new ContentBlock($block);
    }

    /**
     * @inheritDoc
     */
    public function getRegisteredBlocks(): array
    {
        $out = [];
        foreach (ContentType::all() as $type) {
            $block = (new ContentBlock)->setContentType($type);
            $out['content.'.$type->machineName] = $block;
        }
        return $out;
    }

    public function render(Block $block): string
    {
        return (new ContentBlockRenderer($block->instance()))->render();
    }
}