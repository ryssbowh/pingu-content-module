<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Validator;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentField;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Entities\Field;
use Pingu\Content\Events\ContentFieldStoreValidator;
use Pingu\Content\Events\ContentFieldUpdateValidator;
use Pingu\Content\Exceptions\ParameterMissing;
use Pingu\Core\Contracts\Controllers\HandlesModelContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\Controllers\HandlesModel;
use Pingu\Forms\Contracts\FormableContract;
use Pingu\Forms\Exceptions\ModelNotFormable;
use Pingu\Forms\Fields\Text;
use Pingu\Forms\FormModel;
use Pingu\Forms\Renderers\Hidden;

class ContentFieldController extends BaseController implements HandlesModelContract
{
    use HandlesModel;

    protected $request;
    protected $contentType;
    protected $fieldType;

    public function __construct(Request $request)
    {
        $this->request = $request;
        if($name = $request->route()->parameter(ContentType::routeSlug())){
            $this->contentType = ContentType::findByName($name);
        }
    }

    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Field::class;
    }

    /**
     * @inheritDoc
     */
    protected function beforeCreate()
    {
        $type = $this->request->input('type', false);
        if(!$type){
            throw new ParameterMissing('type', 'get');
        }
        $fieldType = \Content::getRegisteredContentField($type);
        $this->fieldType = new $fieldType;

        if(!$this->fieldType instanceof FormableContract){
            throw new ModelNotFormable($this->fieldType);
        }
    }
    
    /**
     * @inheritDoc
     */
    protected function beforeStore()
    {
        $type = $this->request->post()['type'] ?? false;
        if(!$type){
            throw new ParameterMissing('type', 'post');
        }
        $fieldType = \Content::getRegisteredContentField($type);
        $this->fieldType = new $fieldType;

        if(!$this->fieldType instanceof FormableContract){
            throw new ModelNotFormable($this->fieldType);
        }
    }
    
    /**
     * @inheritDoc
     */
    protected function getStoreUrl()
    {
        return ContentType::transformAdminUri('storeField', [$this->contentType], true);
    }

    /**
     * @inheritDoc
     */
    protected function getStoreModel()
    {
        return get_class($this->fieldType);
    }

    /**
     * @inheritDoc
     */
    protected function modifyCreateForm(FormModel $form)
    {
        $field = new Field;
        $form->addFields($field->getAddFormFields(), $field)
            ->moveFieldUp('machineName')
            ->moveFieldUp('name');

        $type = $this->request->input('type');

        $form->addField('type', [
            'type' => Text::class,
            'renderer' => Hidden::class,
            'default' => $type
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getStoreValidator(string $model)
    {
        $model = new $model;
        $field = new Field;
        $fieldFields = $field->getAddFormFields();
        $rules = array_merge(
            $model->getValidationRules($model->getAddFormFields()), 
            $field->getValidationRules($fieldFields)
        );
        $messages = array_merge(
            $model->getValidationMessages(), 
            $field->getValidationMessages()
        );
        $validator = \Validator::make($this->request->post(), $rules, $messages);
        
        $type = $this->contentType;
        $validator->after(function($validator) use ($type, $fieldFields){
            /**
             * Modify the validator so we can check if 'title' or 'published' which are reserved 
             * (because they are added by the system on every content) are not used
             * and if the machine name is unique for that content type
             */
            $names = $type->getAllFieldsMachineNames();
            $name = $validator->getData()['machineName'];
            if(in_array($name, (new Content)->getAddFormFields())){
                $validator->errors()->add($name, "Machine name $name is reserved by the system");
            }
            if(in_array($name, $names)){
                $validator->errors()->add($name, "Machine name $name already exists for that content type");
            }
        });

        event(new ContentFieldStoreValidator($validator, $type, $model));

        return $validator;
    }

    /**
     * @inheritDoc
     */
    protected function performStore(BaseModel $model, array $validated)
    {
        $fieldDefs = (new Field)->getAddFormFields();
        $fieldFields = array_intersect_key($validated, array_flip($fieldDefs));
        $field = new Field($fieldFields);
        $instanceFields = array_diff_key($validated, array_flip($fieldDefs));
        $model->saveWithRelations($instanceFields);
        $field->content_type()->associate($this->contentType);
        $model->field()->save($field);
    }

    /**
     * @inheritDoc
     */
    protected function onSuccessfullStore(BaseModel $field)
    {
        return redirect(ContentType::transformAdminUri('listFields', [$this->contentType], true));
    }

    /**
     * @inheritDoc
     */
    protected function onModelCreated(BaseModel $field)
    {
        \Notify::success($field::friendlyName().' field '.$field->field->name.' has been saved');
    }

    /**
     * @inheritDoc
     */
    protected function addVariablesToCreateView(array &$with)
    {
        $with['title'] = 'Add a '.$this->fieldType::friendlyname().' field';
    }

    /**
     * @inheritDoc
     */
    protected function addVariablesToEditView(array &$with, BaseModel $model)
    {
        $with['title'] = 'Edit field '.$model->field->name;
    }

    /**
     * @inheritDoc
     */
    protected function getEditModel(BaseModel $field)
    {
        return $field->instance;
    }

    /**
     * @inheritDoc
     */
    protected function modifyEditForm(FormModel $form, BaseModel $field)
    {
        $form->addFields($field->field->getEditFormFields(), $field->field)
            ->moveFieldUp('name');
    }

    /**
     * @inheritDoc
     */
    protected function getUpdateValidator(BaseModel $model)
    {
        $rules = array_merge(
            $model->getValidationRules($model->getEditFormFields()), 
            $model->field->getValidationRules($model->field->getEditFormFields())
        );
        $messages = array_merge(
            $model->getValidationMessages(),
            $model->field->getValidationMessages()
        );
        $validator = \Validator::make($this->request->post(), $rules, $messages);

        event(new ContentFieldUpdateValidator($validator, $model));

        return $validator;
    }
    /**
     * @inheritDoc
     */
    protected function getUpdateUrl(BaseModel $field)
    {
        return $field->field::transformAdminUri('update', [$field->field], true);
    }

    /**
     * @inheritDoc
     */
    protected function onSuccessfullUpdate(BaseModel $field)
    {
        return redirect($field->field->content_type::transformAdminUri('listFields', [$field->field->content_type], true));
    }

    /**
     * @inheritDoc
     */
    protected function performUpdate(BaseModel $field, array $validated)
    {
        $fieldFields = $field->field->getEditFormFields();
        $fields = $field->getEditFormFields();
        $fieldValues = array_diff_key($validated, array_flip($fields));
        $values = array_diff_key($validated, array_flip($fieldFields));

        $field->field->formFill($fieldValues);
        $field->field->save();
        
        return ($field->field->getChanges() or $field->saveWithRelations($values));
    }

    /**
     * @inheritDoc
     */
    protected function onModelUpdatedWithoutChanges(BaseModel $field)
    {
        \Notify::info('No changes made to field '.$field->field->name);
    }

    /**
     * @inheritDoc
     */
    protected function onModelUpdatedWithChanges(BaseModel $field)
    {
        \Notify::success('Field '.$field->field->name.' has been saved');
    }

}
