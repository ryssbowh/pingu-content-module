<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Entities\Field;
use Pingu\Core\Http\Controllers\BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AjaxContentTypeFieldsController extends BaseController
{
    public function patch(Request $request, ContentType $type)
    {
        $models = $request->post()['models'] ?? false;
        if(!$models){
            throw new TypeParameterMissing('models', 'post');
        }
        foreach($models as $data){
            $field = Field::findOrFail($data['id']);
            $field->weight = $data['weight'];
            $field->save();
        }
        return ['message' => 'Fields have been updated'];
    }

    public function delete(Request $request, Field $field)
    {
        $field->delete();
        return ['message' => "Field was deleted"];
    }
}
