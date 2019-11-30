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
use Pingu\Entity\Entities\Entity;
use Pingu\Entity\Traits\IsBundle;

class ContentType extends Entity
{
    use HasMachineName,
        CreatedBy,
        UpdatedBy,
        IsBundle;

    protected $fillable = ['name', 'machineName','description'];

    protected $visible = ['id', 'name', 'machineName','description'];

    protected $observables = ['bundleCreated'];

    public $adminListFields = ['name', 'description'];

    protected $dispatchesEvents =[
        'deleted' => ContentTypeDeleted::class,
        'created' => ContentTypeCreated::class
    ];

    /**
     * @inheritDoc
     */
    public function getRouteKeyName()
    {
        return 'machineName';
    }

    public function bundleName(): string
    {
        return 'content.'.$this->machineName;
    }

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

    public function createEntity(array $values): Entity
    {
        $content = new Content();
        $content->content_type()->associate($this);
        $content->creator()->associate(\Auth::user());
        $content->slug = $content->generateSlug(Str::slug($values['title']));
        $content->save();
        $content->saveFieldValues($values);
        return $content;
    }

    public static function bundleClass()
    {
        return ContentTypeBundle::class;
    }
}
