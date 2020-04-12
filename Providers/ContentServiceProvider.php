<?php

namespace Pingu\Content\Providers;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Routing\Router;
use Pingu\Content\BlockProviders\ContentBlockProvider;
use Pingu\Content\Bundles\ContentTypeBundle;
use Pingu\Content\Config\ContentSettings;
use Pingu\Content\Content;
use Pingu\Content\Entities\Content as ContentModel;
use Pingu\Content\Entities\ContentType;
use Pingu\Content\Events\ContentTypeCreated;
use Pingu\Content\Listeners\ContentTypeCreated as ContentTypeCreatedListener;
use Pingu\Content\Observers\ContentTypeObserver;
use Pingu\Content\Policies\ContentPolicy;
use Pingu\Core\Support\ModuleServiceProvider;

class ContentServiceProvider extends ModuleServiceProvider
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

    protected $entities = [
        ContentType::class,
        ContentModel::class
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(Router $router, Gate $gate)
    {
        $this->registerEntities($this->entities);
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadModuleViewsFrom(__DIR__ . '/../Resources/views', 'content');
        $this->registerFactories();
        
        ContentType::observe(ContentTypeObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        \Settings::register(new ContentSettings, $this->app);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        \Blocks::registerProvider(ContentBlockProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'content'
        );
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('content.php')
        ], 'content-config');
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
