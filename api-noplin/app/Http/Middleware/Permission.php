<?php

namespace App\Http\Middleware;

use App\Constants\Constant;
use App\Constants\ErrorCode;
use App\Exceptions\BusinessException;
use Closure;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use Illuminate\Http\Request;

class Permission extends Middleware
{
    protected array $nameList = [

    ];

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws BusinessException
     */
    public function handle($request, Closure $next): mixed
    {
        $name = $request->route()->getName();
        if (in_array($name, $this->nameList) && auth()->user()['type'] == Constant::USER_TYPE_2) {
            throw new BusinessException('当前用户没有该权限', ErrorCode::NO_PERMISSION);
        }
        return $next($request);
    }
}
