<?php

namespace Pingu\Content\Entities\Fields;

use Pingu\Content\Contracts\ContentFieldContract;
use Pingu\Content\Traits\ContentField;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Fields\Checkbox;
use Pingu\Forms\Support\Fields\NumberInput;
use Pingu\Forms\Support\Types\_Float;
use Pingu\Forms\Traits\Models\Formable;

class FieldFloat extends BaseModel implements ContentFieldContract
{
    use ContentField, Formable;

    protected $fillable = ['precision', 'default', 'required'];

    protected $casts = [
        'required' => 'boolean'
    ];

    /**
     * @inheritDoc
     */
    public function formAddFields()
    {
        return ['precision', 'default', 'required'];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return ['precision', 'default', 'required'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'default' => [
                'field' => NumberInput::class,
                'options' => [
                    'type' => _Float::class
                ],
                'attributes' => [
                    'step' => 0.000001
                ]
            ],
            'precision' => [
                'field' => NumberInput::class
            ],
            'required' => [
                'field' => Checkbox::class
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationRules()
    {
        return [
            'precision' => 'integer|min:0',
            'default' => 'numeric',
            'required' => 'boolean'
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationMessages()
    {
        return [
            'precision.required' => 'Precision is required'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function friendlyName()
    {
    	return 'Float';
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinition()
    {
        return [
            'field' => NumberInput::class,
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
        return ($this->required ? 'required|' : '') . 'numeric';
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
        return 'float';
    }
}
