<?php

namespace Pingu\Content\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Pingu\Content\Bundles\ContentTypeBundle;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Events\ContentCreated;
use Pingu\Content\Events\ContentCreating;
use Pingu\Content\Events\ContentDeleted;
use Pingu\Content\Events\ContentDeleting;
use Pingu\Content\Events\ContentSaved;
use Pingu\Content\Events\ContentSaving;
use Pingu\Content\Events\ContentUpdated;
use Pingu\Content\Events\ContentUpdating;
use Pingu\Core\Traits\Models\CreatedBy;
use Pingu\Core\Traits\Models\DeletedBy;
use Pingu\Core\Traits\Models\UpdatedBy;
use Pingu\Entity\Contracts\BundleContract;
use Pingu\Entity\Contracts\HasViewModesContract;
use Pingu\Entity\Support\BundledEntity;
use Pingu\Entity\Traits\HasViewModes;
use Pingu\Field\Contracts\HasRevisionsContract;
use Pingu\Field\Traits\HasRevisions;

class Content extends BundledEntity implements HasRevisionsContract, HasViewModesContract
{
    use SoftDeletes,
        CreatedBy,
        DeletedBy,
        UpdatedBy,
        HasRevisions,
        HasViewModes;

    /**
     * @inheritDoc
     */
    protected $dispatchesEvents = [
        'deleted' => ContentDeleted::class,
        'created' => ContentCreated::class,
        'creating' => ContentCreating::class,
        'saving' => ContentSaving::class,
        'saved' => ContentSaved::class,
        'deleting' => ContentDeleting::class,
        'updating' => ContentUpdating::class,
        'updated' => ContentUpdated::class,
    ];

    /**
     * @inheritDoc
     */
    protected $fillable = ['title', 'slug', 'published'];

    /**
     * @inheritDoc
     */
    protected $with = ['content_type', 'createdBy'];

    /**
     * @inheritDoc
     */
    protected $visible = ['id', 'content_type', 'createdBy', 'created_at', 'updated_at'];

    /**
     * @inheritDoc
     */
    public $adminListFields = ['title', 'content_type', 'published', 'created_at'];

    /**
     * @inheritDoc
     */
    public $filterable = ['content_type', 'published'];

    /**
     * @inheritDoc
     */
    public $descriptiveField = 'title';

    /**
     * Friendly content type mutator
     * 
     * @return string
     */
    public function friendlyContentTypeAttribute()
    {
        return $this->content_type->name;
    }

    /**
     * Friendly published mutator
     * 
     * @return string
     */
    public function friendlyPublishedAttribute()
    {
        return $this->published ? 'Yes' : 'No';
    }

    /**
     * @inheritDoc
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

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
    protected function bundleInstance(): ?BundleContract
    {
        if ($this->exists and $this->content_type) {
            $class = $this->bundleClass();
            return new $class($this->content_type);
        }
        return null;
    }

    /**
     * Content type relation
     * 
     * @return ContentType
     */
    public function content_type()
    {
        return $this->belongsTo(ContentType::class);
    }
}
