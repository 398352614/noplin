<?php
/**
 * 线路任务 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Validation;


class UploadValidation extends BaseValidation
{
    public array $customAttributes = [
    ];


    public array $rules = [
        'name'=>'nullable|string',
        'file' => 'required|file|mimes:jpg,jpeg,bmp,png,txt,xls,xlsx,doc,docx,jpg,jpeg,bmp,png,pdf',
        'type'=>'required|integer|in:1,2,3'
    ];

    public array $scene = [
        'store' => ['file', 'name', 'type'],
    ];

    public array $message = [

    ];
}

