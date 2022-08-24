<?php


namespace App\Http\Controllers;

use App\Services\BaseService;


/**
 * Class AddressController
 * @package App\Http\Controllers\Admin
 * @property BaseService $service
 */
class CommonController extends BaseController
{
    public function __construct(BaseService $service)
    {
        parent::__construct($service);
    }

    /**
     * @return mixed
     */
    public function dictionary(): mixed
    {
        $modelName = ucfirst($this->data['model']);
        $baseNamespace = 'App\Models\\';
        //将控制器替换成验证类
        //合成验证类
        $modelClass = $baseNamespace . $modelName;
        if (!class_exists($modelClass)) {
            return [];
        }
        $model = new $modelClass();
        return $model->dictionary;
    }
}