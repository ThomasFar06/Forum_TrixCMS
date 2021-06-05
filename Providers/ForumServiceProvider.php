<?php

namespace Extensions\Plugins\Forum_arckene__421339390\Providers;

use Extensions\Plugins\Forum_arckene__421339390\App\Middleware\CheckAuth;
use Extensions\Plugins\Forum_arckene__421339390\App\Middleware\CheckUses;

use App\System\Extensions\Plugin\Core\PluginServiceProvider as ServiceProvider;

class ForumServiceProvider extends ServiceProvider
{

    // Https://Docs.TrixCMS.Eu

    protected $pluginName = "Forum_arckene__421339390";

    protected $middleware = []; // Load simple middleware

    protected $middlewareGroups = []; // Load middleware groups

    protected $middlewarePriority = []; // Load priority middleware

    protected $middlewareRoute = [
        'forum_checkAuth' => CheckAuth::class,
        'forum_checkUses' => CheckUses::class
    ]; // Load route middleware

    /**
     * Register any application services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->registerAllMiddlewares(); // Register all middlewares
        $this->loadRoutes(); // Load all routes from RouteServiceProvider of this plugin
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews(); // Load all views from this plugin
        $this->loadMigrations(); // Load all migrations from this plugin
        $this->loadFactories(); // Load all factories from this plugin
        $this->loadTranslations(); // Load all translations from this plugin

        $this->registerAdminNavbar(); // Register admin navbar
        $this->registerUserRoutes(); // Register user navbar
    }
}