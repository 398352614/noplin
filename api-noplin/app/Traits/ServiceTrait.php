<?php

namespace App\Traits;


use App\Services\AuthService;
use App\Services\FileService;
use App\Services\UserService;
use Illuminate\Contracts\Container\BindingResolutionException;

trait ServiceTrait
{
    //为了让service不提示代码错误，只能以以下格式写
    use FactoryInstanceTrait;

    public function authService(): AuthService
    {
        try {
            return self::getInstance(AuthService::class);
        } catch (BindingResolutionException $e) {
        }
    }

    public function userService(): UserService
    {
        try {
            return self::getInstance(UserService::class);
        } catch (BindingResolutionException $e) {
        }
    }


    public function fileService(): FileService
    {
        try {
            return self::getInstance(FileService::class);
        } catch (BindingResolutionException $e) {
        }
    }


}
