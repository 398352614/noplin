<?php


namespace App\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use JetBrains\PhpStorm\ArrayShape;

/**
 * 为了能继承baseModel所以做成模型
 * Class Auth
 * @package App\Models
 */
class Auth extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    /**
     * 为了实现jwt扩展以进行身份验证
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * 为了实现jwt扩展以进行身份验证
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    #[ArrayShape(['role' => "string"])]
    public function getJWTCustomClaims(): array
    {
        return [
            'role' => 'admin',
        ];
    }
}