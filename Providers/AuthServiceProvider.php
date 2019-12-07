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
    protected $policies = [];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  Gate $gate
     * @return void
     */
    public function boot(Gate $gate)
    {
        // $this->registerPolicies($gate);
    }
} 