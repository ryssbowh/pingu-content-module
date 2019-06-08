<?php

namespace Pingu\Content\Providers;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Pingu\Content\Content;
use Pingu\Content\Entities\Fields\FieldBoolean;
use Pingu\Content\Entities\Fields\FieldDatetime;
use Pingu\Content\Entities\Fields\FieldEmail;
use Pingu\Content\Entities\Fields\FieldFloat;
use Pingu\Content\Entities\Fields\FieldInteger;
use Pingu\Content\Entities\Fields\FieldText;
use Pingu\Content\Entities\Fields\FieldTextLong;
use Pingu\Content\Entities\Fields\FieldUrl;
use Pingu\Content\Events\ContentTypeCreated;
use Pingu\Content\Http\Middleware\DeletableContentField;
use Pingu\Content\Http\Middleware\EditableContentField;
use Pingu\Content\Listeners\ContentTypeCreated as ContentTypeCreatedListener;
use Pingu\Content\Policies\ContentPolicy;

class ContentServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $listen = [
        ContentTypeCreated::class => [
            ContentTypeCreatedListener::class,
        ]
    ];

    protected $modelFolder = 'Entities';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(Router $router, Gate $gate)
    {
        $this->registerModelSlugs();
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'content');
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerContentFields();

        $router->aliasMiddleware('deletableContentField', DeletableContentField::class);
        $router->aliasMiddleware('editableContentField', EditableContentField::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('content.content', Content::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
    }

    protected function registerContentFields()
    {
        \Content::registerContentFields([
            FieldBoolean::class,
            FieldDatetime::class,
            FieldEmail::class,
            FieldFloat::class,
            FieldInteger::class,
            FieldText::class,
            FieldTextLong::class,
            FieldUrl::class
        ]);
    }

    /**
     * Registers all the slugs for this module's models
     */
    public function registerModelSlugs()
    {
        \ModelRoutes::registerSlugsFromPath(realpath(__DIR__.'/../'.$this->modelFolder));
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('content.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'content'
        );
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/content');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'content');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'content');
        }
    }

    /**
     * Register an additional directory of factories.
     * 
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}