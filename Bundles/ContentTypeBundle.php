<?php

namespace Pingu\Content\Bundles;

use Illuminate\Database\Eloquent\Collection;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Entity\Support\EntityBundle;

class ContentTypeBundle extends EntityBundle
{
    /**
     * @inheritDoc
     */
    public static function entityClass(): string
    {
        return ContentType::class;
    }

    /**
     * @inheritDoc
     */
    public function bundleFriendlyName(): string
    {
        return $this->entity->name;
    }

    /**
     * @inheritDoc
     */
    public function entityFor(): string
    {
        return Content::class;
    }

    /**
     * @inheritDoc
     */
    public function entities(): Collection
    {
        return $this->entity->contents;
    }
}