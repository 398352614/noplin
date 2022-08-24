<?php

namespace App\Http\Middleware;

use App\Constants\ErrorCode;
use App\Exceptions\BusinessException;
use App\Traits\JsonToArrayTrait;
use App\Validation\BaseValidation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use JetBrains\PhpStorm\Pure;

class Validate
{
    use JsonToArrayTrait;

    public static string $baseNamespace = 'App\\Validation';

    protected BaseValidation $validation;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws BusinessException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $data = $request->all();
        $action = $request->route()->getAction();
        try {
            //替换命名空间
            $baseNamespace = str_replace('App\\Http\\Controllers', self::$baseNamespace, $action['namespace']);
            list($controller, $method) = explode('@', $action['controller']);
            //将控制器替换成验证类
            $controllerName = str_replace('Controller', 'Validation', substr($controller, (strrpos($controller, '\\') + 1)));
            //合成验证类
            $validateClass = $baseNamespace . '\\' . $controllerName;
            //若不存在验证规则和场景,则不验证
            if (!class_exists($validateClass) || !property_exists($validateClass, 'rules') || !property_exists($validateClass, 'scene')) {
                return $next($request);
            }
            /************************************验证规则获取 start****************************************************/
            //获取验证规则
            $this->validation = new $validateClass();
            //若验证规则或场景为空,也不验证
            if (empty($this->validation->rules) || empty($this->validation->scene[$method])) {
                return $next($request);
            }
            //获取验证规则
            $rules = $this->getRules($this->validation->rules, $this->validation->scene[$method]);
            /************************************验证规则获取 end******************************************************/
            /********************************************数据验证 start************************************************/
            //验证
            $this->validate($data, $rules, [...BaseValidation::$baseMessage, $this->validation->message], $this->validation->customAttributes ?? [], $request);
            /*********************************************数据验证 end*************************************************/
        } catch (\Exception $ex) {
            throw new BusinessException($ex->getMessage(), $ex->getCode());
        }
        return $next($request);
    }

    /**
     * 获取验证规则
     * @param $rules
     * @param $scene
     * @return array
     */
    #[Pure] public function getRules($rules, $scene): array
    {
        return Arr::only($rules, $scene);
    }

    /**
     * 验证
     * @param $data
     * @param $rules
     * @param $message
     * @param $customAttributes
     * @param Request $request
     * @throws ValidationException
     * @throws BusinessException
     */
    private function validate($data, $rules, $message, $customAttributes, Request $request)
    {
        $data = $this->jsonToArray($data);
        //规则验证
        $validator = Validator::make($data, $rules, $message, $customAttributes);
        if ($validator->fails()) {
            $messageList = Arr::flatten($validator->errors()->getMessages());
            throw new BusinessException(implode(';', $messageList), ErrorCode::VALIDATE_ERROR);
        }
        request()->validated = $validator->validated(); // 控制器先被初始化,然后才进入的中间件!!!
    }
}
