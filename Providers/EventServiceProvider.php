<?php

namespace Pingu\Content\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Pingu\Content\Events\ContentFieldCreated;
use Pingu\Content\Events\ContentTypeCreated;
use Pingu\Content\Events\ContentTypeDeleted;
use Pingu\Content\Events\CreatingContent;
use Pingu\Content\Listeners\CreateContentTypeFields;
use Pingu\Content\Listeners\CreateContentTypePermissions;
use Pingu\Content\Listeners\CreateExistingContentFieldValues;
use Pingu\Content\Listeners\DeleteContentTypePermissions;
use Pingu\Content\Listeners\Form;
use Pingu\Content\Listeners\GenerateContentSlug;
use Pingu\Content\Listeners\PreventFieldDeletion;
use Pingu\Forms\Events\FormBuilt;

class EventServiceProvider extends ServiceProvider
{
    // protected $listen = [
    //     ContentTypeCreated::class => [
    //         CreateContentTypePermissions::class,
    //         CreateContentTypeFields::class
    //     ],
    //     ContentTypeDeleted::class => [
    //         DeleteContentTypePermissions::class
    //     ],
    //     ContentFieldCreated::class => [
    //     	CreateExistingContentFieldValues::class
    //     ]
    // ];
}
