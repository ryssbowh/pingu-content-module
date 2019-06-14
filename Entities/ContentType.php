<?php

namespace Pingu\Content\Entities;

use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\Field;
use Pingu\Content\Events\ContentTypeCreated;
use Pingu\Content\Events\ContentTypeDeleted;
use Pingu\Core\Contracts\Models\HasAdminRoutesContract;
use Pingu\Core\Contracts\Models\HasContextualLinksContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasAdminRoutes;
use Pingu\Core\Traits\Models\HasAjaxRoutes;
use Pingu\Core\Traits\Models\HasRouteSlug;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Support\Types\Text;
use Pingu\Forms\Traits\Models\Formable;
use Pingu\Jsgrid\Contracts\Models\JsGridableContract;
use Pingu\Jsgrid\Fields\Text as JsGridText;
use Pingu\Jsgrid\Traits\Models\JsGridable;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;

class ContentType extends BaseModel implements JsGridableContract, HasAdminRoutesContract, HasContextualLinksContract
{
	use Formable, JsGridable, HasAjaxRoutes, HasAdminRoutes, HasRouteSlug;

    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            if(is_null($model->titleField)) $model->titleField = config('content.content_types.titleField');
        });
    }

    protected $dispatchesEvents = [
        'created' => ContentTypeCreated::class,
        'deleted' => ContentTypeDeleted::class
    ];

    protected $fillable = ['name', 'machineName','description', 'titleField'];

    protected $visible = ['id', 'name', 'machineName','description'];

    /**
     * @inheritDoc
     */
    public function formAddFields()
    {
        return ['name', 'machineName','description','titleField'];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return ['name','description','titleField'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'name' => [
                'field' => TextInput::class,
                'options' => [
                    'type' => Text::class
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            'description' => [
                'field' => TextInput::class,
                'options' => [
                    'type' => Text::class
                ],
            ],
            'titleField' => [
                'field' => TextInput::class,
                'options' => [
                    'label' => 'Title field name',
                    'default' => config('content.content_types.titleField','Title'),
                    'type' => Text::class
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            'machineName' => [
                'field' => TextInput::class,
                'attributes' => [
                    'class' => 'js-dashify',
                    'data-dashifyfrom' => 'name',
                    'required' => true,
                    'type' => Text::class
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
            'name' => 'required|string',
            'titleField' => 'required|string',
            'description' => 'sometimes|string',
            'machineName' => 'required|unique:content_types,machineName,{machineName}'
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationMessages()
    {
        return [
            'name.required' => 'Name is required',
            'titleField.required' => 'Title field name is required',
            'machineName.required' => 'Machine Name is required',
            'machineName.unique' => 'Machine name already exists'
        ];
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
    public function jsGridFields()
    {
    	return [
    		'name' => [
    			'type' => JsGridText::class
    		]
    	];
    }

    /**
     * @inheritDoc
     */
    public function getContextualLinks(): array
    {
        return [
            'edit' => [
                'title' => 'Edit',
                'url' => $this::transformAdminUri('edit', [$this], true)
            ],
            'fields' => [
                'title' => 'Fields',
                'url' => $this::transformAdminUri('listFields', [$this], true)
            ]
        ];
    }

    /**
     * admin uri to list this content type's fields
     * @return string
     */
    public static function adminListFieldsUri()
    {
    	return static::routeSlug().'/{'.static::routeSlug().'}/fields';
    }

    /**
     * admin uri to store a field on this content type
     * @return string
     */
    public static function adminStoreFieldUri()
    {
        return static::routeSlug().'/{'.static::routeSlug().'}/fields';
    }

    /**
     * admin uri to patch fields for this content type
     * @return string
     */
    public static function ajaxPatchFieldsUri()
    {
        return static::routeSlug().'/{'.static::routeSlug().'}/fields';
    }

    /**
     * @inheritDoc
     */
    public static function adminAddFieldUri()
    {
    	return static::routeSlug().'/{'.static::routeSlug().'}/fields/create';
    }

    /**
     * Get all fields associated to this content type
     * @return Relation
     */
    public function fields()
    {
        return $this->hasMany(Field::class)->orderBy('weight', 'asc');
    }

    /**
     * Get all content associated to this content type
     * @return Relation
     */
    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    /**
     * Get all fields (morphed) associated to this content type
     * @return Collection
     */
    public function getFields()
    {
        return $this->fields->map(function($field){
            return $field->instance;
        });
    }

    /**
     * Gets the machine names of all the fields defined for that content type
     * @return array
     */
    public function getAllFieldsMachineNames()
    {
        return $this->fields->pluck('machineName')->toArray();
    }

    /**
     * Find a content type by machine name
     * @param  string $name
     * @return null|ContentType
     */
    public static function findByName(string $name)
    {
        return static::where(['machineName' => $name])->first();
    }
}
