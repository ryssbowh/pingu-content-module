<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Events\ContentValidatorCreated;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Forms\Fields\Boolean;
use Pingu\Forms\Fields\Text;
use Pingu\Forms\Form;
use Pingu\Jsgrid\Traits\Controllers\JsGrid;

class AdminContentController extends BaseController
{
    public function createIndex()
    {
        $types = ContentType::all();
        $available = [];
        foreach($types as $type){
            if(\Auth::user()->can('create '.Str::plural($type->machineName))){
                $available[] = $type;
            }
        }
        return view('content::create')->with([
            'types' => $available,
            'content' => Content::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request, ContentType $type)
    {
        $form = \Content::getCreateContentForm($type);

        return view('content::addContent')->with([
            'form' => $form,
            'title' => 'Add a '.$type->name,
            'contentType' => $type
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, ContentType $type)
    {
        $validator = $this->makeContentValidator($request, $type);
        $validator->validate();
        $validated = $validator->validated();

        $content = \Content::createContent($type, $validated);

        \Notify::success($type->name." '".$content->title."' has been created");

        return redirect()->route('content.admin.content');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function edit(Request $request, Content $content)
    {
        $form = \Content::getEditContentForm($content);

        return view('content::editContent')->with([
            'form' => $form,
            'title' => 'Edit '.$content->title,
            'contentType' => $content->content_type,
            'content' => $content,
            'deleteUri' => $content::transformAdminUri('confirmDestroy', [$content], true)
        ]);
    }

    /**
     * Updates a content
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, Content $content)
    {
        $validator = $this->makeContentValidator($request, $content->content_type);
        $validator->validate();
        $validated = $validator->validated();

        $content = \Content::updateContent($content, $validated);

        \Notify::success($content->title."' has been updated");

        return redirect()->route('content.admin.content');
    }

    /**
     * Make a validator for a content type content
     * @param  Request     $request
     * @param  ContentType $type
     * @return Validator
     */
    protected function makeContentValidator(Request $request, ContentType $type)
    {
        $rules = ['title' => 'required|string', 'published' => 'boolean'];
        $messages = ['title.required' => $type->titleField.' is required'];

        foreach($type->fields as $field){
            $rules[$field->machineName] = $field->instance->getFieldValidationRules();
            foreach($field->instance->getFieldValidationMessages() as $name => $message){
                $messages[$field->machineName.'.'.$name] = $message;
            }
        }

        $validator = \Validator::make($request->post(), $rules, $messages);
        event(new ContentValidatorCreated($validator, $type));

        return $validator;
    }

    /**
     * Confirm deleteion
     * @param Content $content
     * @return view
     */
    public function confirmDestroy(Content $content)
    {   
        $form = new Form([
            'delete-content',
            ['url' => Content::transformAdminuri('destroy', [$content]), 'method' => "DELETE"],
            [],
            []
        ]);
        return view('content::deleteContent')->with([
            'content' => $content,
            'form' => $form
        ]);
    }
}
