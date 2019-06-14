<?php

namespace Pingu\Content\Entities;

use Illuminate\Support\Str;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Entities\FieldValue;
use Pingu\Content\Events\ContentCreated;
use Pingu\Content\Events\CreatingContent;
use Pingu\Core\Contracts\Models\HasAdminRoutesContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasAdminRoutes;
use Pingu\Core\Traits\Models\HasAjaxRoutes;
use Pingu\Core\Traits\Models\HasRouteSlug;
use Pingu\Forms\Support\Fields\Checkbox;
use Pingu\Forms\Support\Fields\ModelSelect;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Traits\Models\Formable;
use Pingu\Jsgrid\Contracts\Models\JsGridableContract;
use Pingu\Jsgrid\Fields\Checkbox as JsGridCheckbox;
use Pingu\Jsgrid\Fields\ModelSelect as JsGridModelSelect;
use Pingu\Jsgrid\Fields\Text as JsGridText;
use Pingu\Jsgrid\Traits\Models\JsGridable;
use Pingu\User\Entities\User;

class Content extends BaseModel implements JsGridableContract, HasAdminRoutesContract
{
    use Formable, JsGridable, HasAdminRoutes, HasAjaxRoutes, HasRouteSlug;

    protected $dispatchesEvents =[
        'creating' => CreatingContent::class,
        'created' => ContentCreated::class
    ];

    protected $casts = [
        'published' => 'boolean'
    ];

    protected $attributes = [
        'published' => true
    ];

    protected $with = ['content_type', 'creator'];

    protected $fillable = ['title', 'published'];

    protected $visible = ['id', 'slug', 'title', 'content_type', 'creator', 'published', 'created_at', 'updated_at'];

    public static $reservedFieldNames = ['id', 'slug', 'title', 'content_type_id', 'creator_id', 'published', 'created_at', 'updated_at'];

    public function generateSlug(?string $slug = null, $first = true)
    {
        if(is_null($slug)) $slug = Str::slug($this->title);

        if($this::where(['slug' => $slug])->first()){
            if($first){
                $slug .= '-1';
            }
            else{
                $elems = explode('-', $slug);
                $num = $elems[sizeof($elems)-1] + 1;
                unset($elems[sizeof($elems)-1]);
                $slug = implode('-', $elems).'-'.$num; 
            }
            return $this->generateSlug($slug, false);
        }
        return $slug;
    }

    /**
     * @inheritDoc
     */
    public static function routeSlug()
    {
        return 'content';
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
    public function formAddFields()
    {
        return ['title', 'published'];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return ['title', 'published'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
    		'title' => [
    			'field' => TextInput::class,
                'attributes' => [
                    'required' => true
                ]
    		],
    		'published' => [
    			'field' => Checkbox::class
    		],
            'content_type' => [
                'field' => ModelSelect::class,
                'options' => [
                    'label' => 'Type',
                    'model' => ContentType::class,
                    'textField' => 'name',
                ]
            ],
            'creator' => [
                'field' => ModelSelect::class,
                'options' => [
                    'model' => User::class,
                    'textField' => 'name'
                ]
            ]
    	];
    }

    /**
     * @inheritDoc
     */
	public function validationRules()
    {
        return [
            'title' => 'required|string',
            'published' => 'boolean'
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationMessages()
    {
        return [
            'title.required' => 'Title is required'
        ];
    }

    /**
     * Creator relation
     * @return User
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Content type relation
     * @return ContentType
     */
    public function content_type()
    {
        return $this->belongsTo(ContentType::class);
    }

    /**
     * values relation
     * @return Collection
     */
    public function values()
    {
        return $this->hasMany(FieldValue::class);
    }

    /**
     * @inheritDoc
     */
    public function jsGridFields()
    {
        return [
            'title' => [
                'type' => JsGridText::class
            ],
            'content_type' => [
                'type' => JsGridModelSelect::class,
                'options' => [
                    'editing' => false
                ]
            ],
            'creator' => [
                'type' => JsGridModelSelect::class,
                'options' => [
                    'editing' => false
                ]
            ],
            'published' => [
                'type' => JsGridCheckbox::class
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public static function adminCreateUri()
    {
        return static::routeSlug().'/{'.ContentType::routeSlug().'}/create';
    }

    /**
     * @inheritDoc
     */
    public static function adminStoreUri()
    {
        return static::routeSlug().'/{'.ContentType::routeSlug().'}';
    }

    /**
     * @inheritDoc
     */
    public static function adminConfirmDestroyUri()
    {
        return static::routeSlug().'/{'.Content::routeSlug().'}/delete';
    }

    /**
     * Override save to generate the slug
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public function save($options = [])
    {
        if($this->isDirty('title')){
            $this->attributes['slug'] = $this->generateSlug();
        }
        parent::save($options);
    }

}
