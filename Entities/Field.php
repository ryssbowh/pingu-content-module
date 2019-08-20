<?php

namespace Pingu\Content\Entities;

use Pingu\Content\Events\ContentFieldCreated;
use Pingu\Content\Events\DeletingContentField;
use Pingu\Core\Contracts\Models\HasCrudUrisContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasBasicCrudUris;
use Pingu\Core\Traits\Models\HasWeight;
use Pingu\Forms\Contracts\Models\FormableContract;
use Pingu\Forms\Support\Fields\NumberInput;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Traits\Models\Formable;

class Field extends BaseModel implements FormableContract, HasCrudUrisContract
{
    use Formable, HasBasicCrudUris;

    protected $dispatchesEvents = [
        'created' => ContentFieldCreated::class
    ];

    protected $fillable = ['editable', 'deletable', 'name', 'machineName','helper','weight'];

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

    public static function boot()
    {
        parent::boot();

        static::saving(function($field){
            if(is_null($field->weight)){
                $field->weight = $field->getNextWeight();
            }
        });
    }

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
        return ['name', 'helper', 'weight'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'name' => [
                'field' => TextInput::class,
                'attributes' => [
                    'required' => true
                ]
            ],
            'helper' => [
                'field' => TextInput::class
            ],
            'weight' => [
                'field' => NumberInput::class
            ],
            'machineName' => [
                'field' => TextInput::class,
                'options' => [
                    'required' => true
                ],
                'attributes' => [
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
            'machineName' => 'required|string',
            'weight' => 'integer'
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

    public function buildFieldDefinition()
    {
        return [
            'options' => [
                'label' => $this->name,
                'helper' => $this->helper
            ],
            'attributes' => [
                'required' => $this->instance->definesField('required') ? $this->instance->required : ''
            ]
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
    public static function editUri()
    {
        return 'content/field/{'.static::routeSlug().'}/edit';
    }

    /**
     * @inheritDoc
     */
    public static function updateUri()
    {
        return 'content/field/{'.static::routeSlug().'}';
    }

    /**
     * @inheritDoc
     */
    public static function deleteUri()
    {
        return 'content/field/{'.static::routeSlug().'}/delete';
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

}
