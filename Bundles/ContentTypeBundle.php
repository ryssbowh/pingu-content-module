<?php

namespace Pingu\Content\Bundles;

use Pingu\Content\Entities\ContentType;
use Pingu\Entity\Support\EntityBundle;

class ContentTypeBundle extends EntityBundle
{
    public static function entityClass(): string
    {
        return ContentType::class;
    }

    public function bundleFriendlyName(): string
    {
        return $this->entity->name;
    }
}