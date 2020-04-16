<?php
$router = app('router');
$router->group([
    'prefix' => config('authapi.prefix'),
], function ($router) {
    $router->post('/sanctum/token', 'AuthController@login');

});
$router->group([
    'prefix' => config('authapi.prefix'),
    'middleware' => config('authapi.middleware'),
], function ($router) {
    $router->get('/sanctum/me', 'AuthController@me')->name('admin.authapi.me');
});

if (!is_null(config('admin'))) {
    $router->group([
        'prefix' => config('admin.route.prefix'),
        'middleware' => config('admin.route.middleware'),
    ], function ($router) {
    });
    $router->group([
        'prefix' => config('admin.route.api_prefix'),
        'namespace' => '\Osi\AuthApi\Controllers',
    ], function ($router) {
        $router->group(['middleware' => config('admin.route.middleware')], function ($router) {
            $router->resource('api/permissions', 'PermissionController')->names('admin.authapi.permissions');
            $router->resource('api/logs', 'LogController')->names('admin.authapi.logs');
            $router->get('api/users', 'AuthController@users')->name('admin.authapi.users');
        });
    });
}
