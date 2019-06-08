<?php

namespace Pingu\Content\Entities;

use Pingu\Content\Events\ContentFieldCreated;
use Pingu\Content\Events\DeletingContentField;
use Pingu\Core\Contracts\Models\HasAdminRoutesContract;
use Pingu\Core\Contracts\Models\HasAjaxRoutesContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasAdminRoutes;
use Pingu\Core\Traits\Models\HasAjaxRoutes;
use Pingu\Core\Traits\Models\HasRouteSlug;
use Pingu\Forms\Contracts\FormableContract;
use Pingu\Forms\Fields\Boolean;
use Pingu\Forms\Fields\Text;
use Pingu\Forms\Traits\Formable;

class Field extends BaseModel implements HasAdminRoutesContract, HasAjaxRoutesContract, FormableContract
{
    use HasAdminRoutes, HasAjaxRoutes, Formable, HasRouteSlug;

    protected $dispatchesEvents = [
        'created' => ContentFieldCreated::class
    ];

    protected $fillable = ['editable', 'deletable', 'name', 'machineName','helper'];

    protected $casts = [
        'deletable' => 'boolean',
        'editable' => 'boolean'
    ];

    protected $attributes = [
        'deletable' => true,
        'editable' => true,
        'helper' => ''
    ];

    protected $with = ['instance'];

    /**
     * @inheritDoc
     */
    public function formAddFields()
    {
        return ['name', 'machineName', 'helper'];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return ['name', 'helper'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'name' => [
                'type' => Text::class,
                'required' => true
            ],
            'helper' => [
                'type' => Text::class
            ],
            'machineName' => [
                'required' => true,
                'type' => Text::class,
                'label' => 'Machine name',
                'rendererAttributes' => [
                    'class' => 'js-dashify',
                    'data-dashifyfrom' => 'name'
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
            'helper' => 'string',
            'machineName' => 'required|string'
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationMessages()
    {
        return [
            'name.required' => 'Name is required',
            'machineName.required' => 'Machine name is required'
        ];
    }

    public function buildContentDefinition($value = null)
    {
        return [
            'type' => $this->instance->fieldType(),
            'label' => $this->name,
            'helper' => $this->helper,
            'default' => $value,
            'required' => $this->instance->definesField('required') ? $this->instance->required : ''
        ];
    }

    /**
     * Morph this field into its instance
     * @return Relation
     */
    public function instance()
    {
    	return $this->morphTo();
    }

    /**
     * Gets this field content type relation
     * @return Relation
     */
    public function content_type()
    {
    	return $this->belongsTo(ContentType::class);
    }

    /**
     * @inheritDoc
     */
    public static function adminEditUri()
    {
        return 'content/field/{'.static::routeSlug().'}/edit';
    }

    /**
     * @inheritDoc
     */
    public static function adminUpdateUri()
    {
        return 'content/field/{'.static::routeSlug().'}';
    }

    /**
     * @inheritDoc
     */
    public static function ajaxDeleteUri()
    {
        return 'content/field/{'.static::routeSlug().'}';
    }

    /**
     * Get the next weight for a field belonging to the same content type
     * @return int
     */
    public function getNextWeight()
    {
        $last = $this->content_type->fields->last();
        if(!$last) return 1;
        return ($last->weight + 1);
    }

    /**
     * Overrides save to add a default weight
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if(is_null($this->weight)){
            $this->weight = $this->getNextWeight();
        }
        return parent::save();
    }
}
