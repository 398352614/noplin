<?php


namespace App\Validation;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

/**
 * Class BaseValidate
 * @package App\Http\Validate
 */
class BaseValidation
{
    public static array $baseMessage = [

    ];

    public array $customAttributes = [];

    public array $rules = [];

    public array $scene = [];

    public array $message = [];


    /**
     * 唯一验证
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param Validator $validator
     * @return bool
     */
    public function uniqueIgnore($attribute, $value, $parameters, Validator $validator): bool
    {
        $table = $parameters[0];
        $primaryKey = $parameters[1];
        $query = DB::table($table);
        if ($id = request()->route($primaryKey)) {
            $query->where($primaryKey, '<>', $id);
        }
        if ($companyIdKey = array_search('station_id', $parameters)) {
            unset($parameters[$companyIdKey]);
            $query->where('station_id', '=', auth()->user()['station_id']);
        }
        //若还有其他字段验证,则增加查询条件
        unset($parameters[0], $parameters[1]);
        if (!empty($parameters)) {
            $params = $validator->attributes();
            foreach ($parameters as $parameter) {
                !empty($params[$parameter]) && $query->where($parameter, '=', $params[$parameter]);
            }
        }
        $model = $query->where($attribute, '=', $value)->first();
        return empty($model);
    }

    /**
     * 验证id列表值是否是合法的
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function idList($attribute, $value, $parameters, $validator): bool
    {
        $maxCount = $parameters[0] ?? 100;
        if (is_string($value)) {
            $list = explode(',', $value);
            if (count($list) > $maxCount) return false;
            $id = Arr::first($list, function ($v) {
                return !is_numeric($v);
            });
        }
        return empty($id);
    }

    /**
     * 验证字段是否包含特殊字符
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function specialChar($attribute, $value, $parameters, $validator): bool
    {
        return have_special_char($value);
    }

    public function upper($attribute, $value, $parameters, $validator): bool
    {
        return strtoupper($value) == $value;
    }

    public function lower($attribute, $value, $parameters, $validator): bool
    {
        return strtolower($value) == $value;
    }

}
