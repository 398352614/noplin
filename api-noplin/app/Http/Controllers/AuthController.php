<?php


namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\AuthService;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class AuthController
 * @package App\Http\Controllers\Admin
 * @property AuthService $service
 */
class AuthController extends BaseController
{
    /**
     * AuthController constructor.
     * @param AuthService $service
     */
    public function __construct(AuthService $service)
    {
        parent::__construct($service);
    }

    /**
     * 登录
     * @return array
     * @throws BusinessException
     */
    public function login(): array
    {
        return $this->service->login($this->data);
    }

    /**
     * 注册
     * @return array|null
     * @throws BindingResolutionException
     * @throws BusinessException
     */
    public function register(): array|null
    {
        return $this->service->register($this->data);
    }

    /**
     * 重置
     * @throws BusinessException
     */
    public function reset()
    {
        $this->service->reset($this->data);
    }

    /**
     * 重置发码
     * @return array
     * @throws BusinessException
     */
    public function getResetCode(): array
    {
        return $this->service->getResetCode($this->data);
    }

    /**
     * 登出
     * @return void
     */
    public function logout(): void
    {
        $this->service->logout();
    }

    /**
     * @throws BusinessException
     */
    public function updatePassword()
    {
        $this->service->updatePassword($this->data);
    }
}