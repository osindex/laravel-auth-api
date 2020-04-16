<?php

namespace Osi\AuthApi\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Osi\AuthApi\Controllers\ResponseBaseTrait;

class Permission
{
    use ResponseBaseTrait;
    /**
     * @var string
     */
    protected $middlewarePrefix = 'authapi.permission:';

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param array $args
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next, ...$args)
    {
        if (config('authapi.permission.check') === false) {
            return $next($request);
        }

        if ($this->shouldNotPassThrough($request)) {
            return $this->forbidden();
        } else {
            return $next($request);
        }
    }

    /**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function shouldNotPassThrough($request)
    {
        $user = $request->user();
        $router = $request->route()->getAction();
        if (isset($router['as'])) {
            $permissions = $user->apiPermissions;
            $status = $permissions
                ->contains(function ($permission) use ($router) {
                    return $permission->router === $router['as'] && !in_array($router['as'], config('authapi.permission.excepts'));
                });
            unset($user->apiPermissions);
            return $status;
        } else {
            return false;
        }

    }
}
