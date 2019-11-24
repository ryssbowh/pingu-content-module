<?php

namespace Pingu\Content\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Entities\Policies\ContentPolicy;
use Pingu\Content\Events\ContentCreated;
use Pingu\Content\Events\CreatingContent;
use Pingu\Core\Traits\Models\CreatedBy;
use Pingu\Core\Traits\Models\DeletedBy;
use Pingu\Core\Traits\Models\UpdatedBy;
use Pingu\Entity\Contracts\HasBundleContract;
use Pingu\Entity\Entities\Entity;
use Pingu\Entity\Traits\IsBundled;

class Content extends Entity implements HasBundleContract
{
    use SoftDeletes,
        CreatedBy,
        DeletedBy,
        UpdatedBy,
        IsBundled;

    protected $dispatchesEvents =[
        'creating' => CreatingContent::class,
        'created' => ContentCreated::class
    ];

    protected $with = ['content_type', 'creator'];

    protected $visible = ['id', 'content_type', 'creator', 'created_at', 'updated_at', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function bundleName(): ?string
    {
        if ($this->content_type) {
            return 'content.'.$this->content_type->machineName;
        }
        return null;
    }

    public function generateSlug(?string $slug = null, $ignore = null, $first = true)
    {
        if(is_null($slug)) $slug = Str::slug($this->getBundleFieldValue('title'));

        if($model = $this::where(['slug' => $slug])->first()){
            if($ignore and $ignore->id == $model->id) return $slug;
            if($first){
                $slug .= '-1';
            }
            else{
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
     * @inheritDoc
     */
    public static function routeSlug(): string
    {
        return 'content';
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

    public function getPolicy(): string
    {
        return ContentPolicy::class;
    }

}
