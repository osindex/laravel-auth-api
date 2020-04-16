<?php

namespace Osi\AuthApi;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use SmallRuralDog\Admin\Admin;

class ExtendServiceProvider extends ServiceProvider
{
    protected $routeMiddleware = [
        'authapi.log' => Middleware\LogOperation::class,
        'authapi.permission' => Middleware\Permission::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'authapi' => [
            'api',
            'auth:sanctum',
            'authapi.log',
            'authapi.permission',
        ],
    ];
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Admin::script('auth-api', __DIR__ . '/../dist/js/extend.js');
        Admin::style('auth-api', __DIR__ . '/../dist/css/extend.css');
        $this->registerPublishing();

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/authapi.php', 'authapi');
        $this->loadAdminAuthConfig();
        $this->registerRouteMiddleware();
        $this->registerRouter(); //
    }
    /**
     * 注册路由
     *
     * @author osi
     */
    private function registerRouter()
    {
        if (strpos($this->app->version(), 'Lumen') === false && !$this->app->routesAreCached()) {
            app('router')->namespace('Osi\AuthApi\Controllers')->group(__DIR__ . '/../routes/route.php');
        } else {

            require __DIR__ . '/../routes/route.php';
        }
    }

    protected function loadAdminAuthConfig()
    {
        config(Arr::dot(config('authapi.api', []), 'api.'));
    }

    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config' => config_path()], 'authapi-config');
            $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'authapi-migrations');
        }
    }
}
