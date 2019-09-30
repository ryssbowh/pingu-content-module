<?php

namespace Pingu\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $contentType = $this->route()->parameters()['content_type'];
        $rules = [];
        foreach($contentType->entityBundleFields() as $field){
            $rules[$field->machineName] = $field->instance->bundleFieldValidationRule();
        }
        return $rules;
    }

    public function messages()
    {
        $contentType = $this->route()->parameters()['content_type'];
        $messages = [];
        foreach($contentType->entityBundleFields() as $field){
            foreach($field->instance->bundleFieldValidationMessages() as $name => $message){
                $messages[$field->machineName.'.'.$name] = $message;
            }
        }
        return $messages;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
