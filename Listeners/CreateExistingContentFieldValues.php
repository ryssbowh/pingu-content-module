<?php

namespace Pingu\Content\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateExistingContentFieldValues
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Creates field values for all content already defined for that content type
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $contents = $event->field->content_type->contents;
        foreach($contents as $content){
            \Content::createFieldValue($event->field, $content, $event->field->instance->default);
        }
    }
}
