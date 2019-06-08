<?php

namespace Pingu\Content\Entities\Fields;

use Pingu\Content\Contracts\ContentFieldContract;
use Pingu\Content\Traits\ContentField;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Fields\Boolean;
use Pingu\Forms\Fields\Number;
use Pingu\Forms\Fields\Text;
use Pingu\Forms\Traits\Formable;

class FieldBoolean extends BaseModel implements ContentFieldContract
{
	use ContentField, Formable;

    protected $fillable = ['default'];

    protected $casts = [
    	'default' => 'boolean'
	];

    /**
     * @inheritDoc
     */
    public function formAddFields()
    {
        return ['default'];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return ['default'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'default' => [
                'type' => Boolean::class
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationRules()
    {
        return [
            'default' => 'boolean',
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
    	return 'Boolean';
    }

    /**
     * @inheritDoc
     */
    public function fieldType()
    {
        return Boolean::class;
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
        return 'boolean';
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
        return 'boolean';
    }
}
