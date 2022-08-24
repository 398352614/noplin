<?php

namespace App\Constants;

use ReflectionClass;
use ReflectionException;

/**
 * @Constants
 */
class ErrorCode
{
    /**
     * 获取类中所有常量
     */
    public static function all(): array
    {
        $constantList = [];
        try {
            $constantList = (array)new ReflectionClass(get_class());
        } catch (ReflectionException) {
        }
        return $constantList;
    }

    //1-程序错误
    //2-权限认证
    //3-第三方错误
    //4-表单验证
    //5-业务逻辑1状态错误2-复杂错误
    //6-数据库错误1新增失败2修改失败3删除失败4操作失败
    public const SERVER_ERROR = 500;

    //1程序错误0-语法错误1-基础功能2-服务器错误
    public const CLASS_NOT_EXIST = 1001;
    public const FUNCTION_NOT_EXIST = 1002;
    public const FUNCTION_UNDEFINED = 1003;
    public const HTTP_METHOD_ERROR = 1004;


    //2权限认证0-底层1-注册2-登录3-登出4-权限5-权限组
    public const AUTH_ERROR = 2001;
    public const TOKEN_EXPIRED = 2002;
    public const NO_PERMISSION = 2003;

    //3第三方接口0-地图1-经纬度2-国家3-货主

    //4表单验证0-简单验证1-数据存在2-数据不存在3-非法参数4-外键约束
    public const VALIDATE_ERROR = 4001;

    //5业务逻辑0状态错误1业务规则
    public const AMOUNT_DIFFERENT = 5001;

    //6数据库操作失败0新增失败1删除失败2修改失败3操作失败
}
