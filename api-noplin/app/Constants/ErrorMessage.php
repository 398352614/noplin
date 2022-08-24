<?php

namespace App\Constants;

/**
 * @Constants
 */
class ErrorMessage
{
    /**
     * 获取类中所有常量
     */
    public static function all(): array
    {
        return [
            ErrorCode::AUTH_ERROR => "认证错误",
            ErrorCode::CLASS_NOT_EXIST => "类不存在",
            ErrorCode::FUNCTION_UNDEFINED => "方法未定义",
            ErrorCode::SERVER_ERROR => "程序错误"
        ];
    }

}
