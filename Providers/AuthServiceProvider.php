<?php

namespace Pingu\Content\Providers;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Pingu\Content\Entities\Content;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Policies\ContentPolicy;
use Pingu\Content\Policies\ContentTypePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Content::class => ContentPolicy::class,
        ContentType::class => ContentTypePolicy::class
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  Gate  $gate
     * @return void
     */
    public function boot(Gate $gate)
    {
        $this->registerPolicies($gate);

        \Gate::define('edit-content', 'Pingu\Content\Policies\ContentPolicy@edit');
        \Gate::define('view-content', 'Pingu\Content\Policies\ContentPolicy@view');
        \Gate::define('delete-content', 'Pingu\Content\Policies\ContentPolicy@delete');
        \Gate::define('create-content', 'Pingu\Content\Policies\ContentTypePolicy@create');
    }
} 