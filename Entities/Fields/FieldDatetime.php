<?php

namespace Pingu\Content\Entities\Fields;

use Pingu\Content\Contracts\ContentFieldContract;
use Pingu\Content\Traits\ContentField;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Fields\Checkbox;
use Pingu\Forms\Support\Fields\Datetime;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Traits\Models\Formable;

class FieldDatetime extends BaseModel implements ContentFieldContract
{
	use ContentField, Formable;

    protected $fillable = ['default', 'format', 'required'];

    protected $casts = [
        'required' => 'boolean'
    ];

    /**
     * @inheritDoc
     */
    public function formAddFields()
    {
        return ['default', 'required', 'format'];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return ['default', 'required', 'format'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'default' => [
                'field' => Datetime::class
            ],
            'required' => [
                'field' => Checkbox::class
            ],
            'format' => [
                'field' => TextInput::class
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationRules()
    {
        return [
            'default' => 'string',
            'required' => 'boolean',
            'format' => 'string'
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationMessages()
    {
        return [];
    }
    
    /**
     * @inheritDoc
     */
    public static function friendlyName()
    {
    	return 'DateTime';
    }

    /**
     * @inheritDoc
     */
    public function fieldType()
    {
        return Datetime::class;
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinition()
    {
        return [
            'field' => Datetime::class,
            'options' => [
                'default' => $this->default
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function fieldValidationRules()
    {
        return $this->required ? 'required' : '';
    }

    /**
     * @inheritDoc
     */
    public function fieldValidationMessages()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function getMachineName()
    {
        return 'datetime';
    }
}
