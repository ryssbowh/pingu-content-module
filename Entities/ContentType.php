<?php

namespace Pingu\Content\Entities;

use Illuminate\Support\Str;
use Pingu\Content\Bundles\ContentTypeBundle;
use Pingu\Content\Context\EditContentTypeContext;
use Pingu\Content\Context\UpdateContentTypeContext;
use Pingu\Content\Entities\Content;
use Pingu\Content\Events\ContentTypeCreated;
use Pingu\Content\Events\ContentTypeDeleted;
use Pingu\Core\Traits\Models\CreatedBy;
use Pingu\Core\Traits\Models\HasMachineName;
use Pingu\Core\Traits\Models\Revisionnable;
use Pingu\Core\Traits\Models\UpdatedBy;
use Pingu\Entity\Support\EntityBundle;

class ContentType extends EntityBundle
{
    use HasMachineName,
        CreatedBy,
        UpdatedBy;

    /**
     * @inheritDoc
     */
    protected $fillable = ['name', 'machineName','description'];

    /**
     * @inheritDoc
     */
    protected $visible = ['id', 'name', 'machineName','description'];

    /**
     * @inheritDoc
     */
    public $adminListFields = ['name', 'description'];

    /**
     * @inheritDoc
     */
    public $descriptiveField = 'name';

    /**
     * @inheritDoc
     */
    protected $dispatchesEvents =[
        'deleted' => ContentTypeDeleted::class,
        'created' => ContentTypeCreated::class
    ];

    public static $routeContexts = [UpdateContentTypeContext::class, EditContentTypeContext::class];

    /**
     * @inheritDoc
     */
    public function bundleClass(): string
    {
        return ContentTypeBundle::class;
    }

    /**
     * @inheritDoc
     */
    public function getRouteKeyName()
    {
        return 'machineName';
    }

    /**
     * Get all content associated to this content type
     * 
     * @return Relation
     */
    public function contents()
    {
        return $this->hasMany(Content::class);
    }
}
