<?php

namespace Pingu\Content\Entities;

use Illuminate\Support\Str;
use Pingu\Content\Bundles\ContentTypeBundle;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\Policies\ContentTypePolicy;
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

    protected $fillable = ['name', 'machineName','description'];

    protected $visible = ['id', 'name', 'machineName','description'];

    public $adminListFields = ['name', 'description'];

    public $descriptiveField = 'name';

    protected $dispatchesEvents =[
        'deleted' => ContentTypeDeleted::class,
        'created' => ContentTypeCreated::class
    ];

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
     * @inheritDoc
     */
    public function getPolicy(): string
    {
        return ContentTypePolicy::class;
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
