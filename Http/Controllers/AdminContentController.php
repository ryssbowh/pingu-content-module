<?php

namespace Pingu\Content\Http\Controllers;

use Illuminate\Support\Str;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Entity\Contracts\BundleContract;
use Pingu\Entity\Traits\Controllers\EntityController;

class AdminContentController extends BaseController
{
    use EntityController;

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

    protected function getStoreUri(BundleContract $bundle): array
    {
        return ['url' => Content::makeUri('store', [$bundle], adminPrefix())];
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    // public function create(Request $request, ContentType $type)
    // {
    //     $form = new AddContentForm($type);

    //     return view('content::addContent')->with([
    //         'form' => $form,
    //         'title' => 'Add a '.$type->name,
    //         'contentType' => $type
    //     ]);
    // }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    // public function store(StoreContentRequest $request, ContentType $type)
    // {
    //     $validated = $request->validated();

    //     $content = \Content::createContent($type, $validated);

    //     \Notify::success($type->name." ".$content->fieldTitle." has been created");

    //     return redirect()->route('content.admin.content');
    // }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    // public function edit(Request $request, Content $content)
    // {
    //     $form = new EditContentForm($content);

    //     return view('content::editContent')->with([
    //         'form' => $form,
    //         'title' => 'Edit '.$content->title,
    //         'contentType' => $content->content_type,
    //         'content' => $content,
    //         'deleteUri' => $content::makeUri('delete', [$content])
    //     ]);
    // }

    /**
     * Updates a content
     * @param Request $request
     * @return Response
     */
    // public function update(UpdateContentRequest $request, Content $content)
    // {
    //     $validated = $request->validated();

    //     $content = \Content::updateContent($content, $validated);

    //     \Notify::success($content->content_type->name.' '.$content->fieldTitle." has been updated");

    //     return redirect()->route('content.admin.content');
    // }
}
