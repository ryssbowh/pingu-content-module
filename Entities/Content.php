<?php

namespace Pingu\Content\Entities;

use Illuminate\Support\Str;
use Pingu\Content\Accessors\ContentAccessor;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Entities\FieldValue;
use Pingu\Content\Events\ContentCreated;
use Pingu\Content\Events\CreatingContent;
use Pingu\Content\Forms\ContentForms;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasBasicCrudUris;
use Pingu\Entity\Contracts\Accessor;
use Pingu\Entity\Contracts\BundleContract;
use Pingu\Entity\Contracts\EntityContract;
use Pingu\Entity\Contracts\EntityFormsBase;
use Pingu\Entity\Traits\Models\Entity;
use Pingu\Entity\Uris\EntityUris;
use Pingu\Forms\Support\Fields\Checkbox;
use Pingu\Forms\Support\Fields\ModelSelect;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Support\Types\Boolean;
use Pingu\Forms\Support\Types\Model;
use Pingu\Forms\Traits\Models\Formable;
use Pingu\Jsgrid\Contracts\Models\JsGridableContract;
use Pingu\Jsgrid\Fields\Checkbox as JsGridCheckbox;
use Pingu\Jsgrid\Fields\ModelSelect as JsGridModelSelect;
use Pingu\Jsgrid\Fields\Text as JsGridText;
use Pingu\Jsgrid\Traits\Models\JsGridable;
use Pingu\User\Entities\User;

class Content extends BaseModel implements JsGridableContract, EntityContract
{
    use Formable, JsGridable, HasBasicCrudUris, Entity;

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

    public function bundle(): BundleContract
    {
        return $this->content_type;
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
    public static function routeSlug()
    {
        return 'content';
    }

    /**
     * @inheritDoc
     */
    public function formAddFields()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'content_type' => [
                'field' => ModelSelect::class,
                'options' => [
                    'type' => Model::class,
                    'label' => 'Type',
                    'model' => ContentType::class,
                    'textField' => 'name',
                ]
            ],
            'creator' => [
                'field' => ModelSelect::class,
                'options' => [
                    'type' => Model::class,
                    'model' => User::class,
                    'textField' => 'name'
                ]
            ],
            'slug' => [
                'field' => TextInput::class
            ]
    	];
    }

    /**
     * @inheritDoc
     */
	public function validationRules()
    {
        return [];
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
    public function jsGridFields()
    {
        return [
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
            'slug' => [
                'type' => JsGridText::class,
                'options' => [
                    'visible' => false
                ]
            ]
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

    public function accessor(): Accessor
    {
        return new ContentAccessor($this);
    }

    public function forms(): EntityFormsBase
    {
        return new ContentForms($this);
    }

    public function afterJsGridFieldsBuilt(array $fields)
    {
        $field = new TextInput('title', [], ['required' => true]);
        $jsField = new JsGridText('#fieldTitle', [], $field);
        array_unshift($fields, $jsField);
        $field = new Checkbox('published', ['type' => Boolean::class], []);
        $jsField = new JsGridCheckbox('#fieldPublished', [], $field);
        $fields[] = $jsField;
        return $fields;
    }

}
