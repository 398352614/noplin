<?php

namespace App\Http\Middleware;

use App\Constants\ErrorCode;
use App\Exceptions\BusinessException;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    private array $except = [

    ];

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     */
    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param string[] ...$guards
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards): mixed
    {
        $routeName = $request->route()->getName();
        if (!in_array($routeName, $this->except)) {
            $this->authenticate($request, $guards);
        }
        return $next($request);
    }

    /**
     * @param Request $request
     * @param array $guards
     * @throws BusinessException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new BusinessException('用户认证失败', ErrorCode::AUTH_ERROR);
    }
}
