<?php

namespace Pingu\Content\Entities\Fields;

use Pingu\Content\Contracts\ContentFieldContract;
use Pingu\Content\Traits\ContentField;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Fields\Boolean;
use Pingu\Forms\Fields\Number;
use Pingu\Forms\Fields\Text;
use Pingu\Forms\Traits\Formable;

class FieldInteger extends BaseModel implements ContentFieldContract
{
    use ContentField, Formable;

    protected $fillable = ['default', 'required'];

    protected $casts = [
        'required' => 'boolean'
    ];

    /**
     * @inheritDoc
     */
    public function formAddFields()
    {
        return ['default', 'required'];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return ['default', 'required'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'default' => [
                'type' => Number::class
            ],
            'required' => [
                'type' => Boolean::class
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationRules()
    {
        return [
            'default' => 'string',
            'required' => 'boolean'
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
    	return 'Integer';
    }

    /**
     * @inheritDoc
     */
    public function fieldType()
    {
        return Number::class;
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinition()
    {
        return [
            'default' => $this->default
        ];
    }

    /**
     * @inheritDoc
     */
    public function fieldValidationRules()
    {
        return ($this->required ? 'required|' : '') . 'integer';
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
        return 'integer';
    }
}
