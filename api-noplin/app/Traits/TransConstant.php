<?php

namespace App\Traits;


use App\Constants\Constant;

class TransConstant
{
    public static array $list = [
        'auth' => [
            'type' => [
                Constant::AUTH_TYPE_1 => "注册",
                Constant::AUTH_TYPE_2 => "重置密码"
            ]
        ],
        'user' => [
            'type' => [
                Constant::USER_TYPE_1 => "超管",
                Constant::USER_TYPE_2 => "管理员"
            ],
            'status' => [
                Constant::USER_STATUS_1 => "启用",
                Constant::USER_STATUS_2 => "禁用",
            ]
        ],

        'file' => [
            'type' => [
                Constant::FILE_TYPE_1 => "文件",
                Constant::FILE_TYPE_2 => "图片",
                Constant::FILE_TYPE_3 => "表格",
            ],
        ],
    ];
}
