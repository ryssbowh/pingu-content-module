<?php

namespace Pingu\Content\Bundles;

use Illuminate\Database\Eloquent\Collection;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Entity\Support\Bundle\ModelBundle;

class ContentTypeBundle extends ModelBundle
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
    public function friendlyName(): string
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

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'content-'.$this->entity->machineName;
    }
}