<?php


namespace App\Validation;

class AuthValidation extends BaseValidation
{
    public array $customAttributes = [

    ];

    public array $rules = [
        'username' => 'required|string|email',
        'password' => 'required|string',
        'confirm_password' => 'required|string',
        'origin_password' => 'required|string|between:8,20',
        'new_password' => 'required|string|between:8,20|different:origin_password',
        'confirm_new_password' => 'required|same:new_password',
        'code' => 'required|string|digits:6',
    ];

    public array $scene = [
        'login' => ['username', 'password'],
        'logout' => ['username', 'password'],
        'register' => ['username', 'password', 'confirm_password', 'code'],
        'registerCode' => ['username'],
        'reset' => ['username', 'new_password', 'confirm_new_password', 'code'],
        'getResetCode' => ['username'],
        'updatePassword' => ['origin_password', 'new_password', 'new_confirm_password'],
    ];

    public array $message = [
    ];
}
