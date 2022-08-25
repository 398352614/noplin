<?php

namespace App\Models;

use App\Constants\Constant;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Auth implements JWTSubject
{

    protected $table = 'user';

    protected $fillable = [
        'username',
        'password',
        'type',
        'status',
        'name',
        'avatar'
    ];

    protected $hidden = [
        'password',
    ];

    public array $dictionary = [
        'type' => [
            Constant::USER_TYPE_1 => "超管",
            Constant::USER_TYPE_2 => "管理员"
        ],
        'status' => [
            Constant::USER_STATUS_1 => "启用",
            Constant::USER_STATUS_2 => "禁用",
        ]
    ];

}
