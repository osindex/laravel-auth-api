<?php

return [
    'prefix' => env('API_ROUTE_PREFIX', 'api'),
    'middleware' => ['authapi'],
    'permission' => [
        'check' => false,
        'excepts' => [
        ],
    ],
    'operation_log' => [
        'enable' => false,
        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],
        /*
         * Routes that will not log to database.
         *
         * All method to path like: admin/auth/logs
         * or specific method to path like: get:admin/auth/logs.
         */
        'except' => [
            'admin/auth/logs*',
        ],
    ],
];
