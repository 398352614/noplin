<?php

namespace App\Constants;

class Constant
{
    /**
     * 各表状态
     */
    const AUTH_TYPE_1 = 1;
    const AUTH_TYPE_2 = 2;

    const FILE_TYPE_1 = 1;
    const FILE_TYPE_2 = 2;
    const FILE_TYPE_3 = 3;

    const USER_STATUS_1 = 1;
    const USER_STATUS_2 = 2;


    const USER_TYPE_1 = 1;
    const USER_TYPE_2 = 2;


    /**
     * 通用状态
     */
    //状态1是2否
    const YES = 1;
    const NO = 2;

    //星期
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 0;

    const SECOND_WEEK = 604800;
    const SECOND_DAY = 86400;

    const CACHE_TAG_USER = 'user';
    const CACHE_TAG_ROOT = 'root';

    const INFINITY = 999999999;

    const REGISTER = 1;
    const RESET = 2;

    const ONLY_TRASH = 1;
    const WITH_TRASH = 2;

    const POST = 1;
    const GET = 2;
    const PUT = 3;
    const DELETE = 4;

    const ENGLISH = 'en';
    const CHINESE = 'cn';
}
