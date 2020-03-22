<?php

namespace Pingu\Content\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Entities\Policies\ContentPolicy;
use Pingu\Content\Events\ContentCreated;
use Pingu\Content\Events\ContentDeleted;
use Pingu\Core\Traits\Models\CreatedBy;
use Pingu\Core\Traits\Models\DeletedBy;
use Pingu\Core\Traits\Models\UpdatedBy;
use Pingu\Entity\Entities\BundledEntity;
use Pingu\Field\Contracts\HasRevisionsContract;
use Pingu\Field\Traits\HasRevisions;

class Content extends BundledEntity implements HasRevisionsContract
{
    use SoftDeletes,
        CreatedBy,
        DeletedBy,
        UpdatedBy,
        HasRevisions;

    protected $dispatchesEvents = [
        'deleted' => ContentDeleted::class,
        'created' => ContentCreated::class
    ];

    protected $fillable = ['title', 'slug', 'published'];

    protected $with = ['content_type', 'createdBy'];

    protected $visible = ['id', 'content_type', 'createdBy', 'created_at', 'updated_at'];

    public $adminListFields = ['title', 'content_type', 'published', 'created_at'];

    public $filterable = ['content_type', 'published'];

    public function friendlyContentTypeAttribute()
    {
        return $this->content_type->name;
    }

    public function friendlyPublishedAttribute()
    {
        return $this->published ? 'Yes' : 'No';
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function bundleName(): ?string
    {
        if ($this->exists and $this->content_type) {
            return 'content.'.$this->content_type->machineName;
        }
        return null;
    }

    public function generateSlug(?string $slug = null, $ignore = null, $first = true)
    {
        if (is_null($slug)) {
            $slug = Str::slug($this->getBundleFieldValue('title'));
        }

        if ($model = $this::where(['slug' => $slug])->first()) {
            if ($ignore and $ignore->id == $model->id) {
                return $slug;
            }
            if ($first) {
                $slug .= '-1';
            } else {
                $elems = explode('-', $slug);
                $num = $elems[sizeof($elems)-1] + 1;
                unset($elems[sizeof($elems)-1]);
                $slug = implode('-', $elems).'-'.$num; 
            }
            return $this->generateSlug($slug, $ignore, false);
        }
        return $slug;
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

    /**
     * @inheritDoc
     */
    public function getPolicy(): string
    {
        return ContentPolicy::class;
    }

}
