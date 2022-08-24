<?php


namespace App\Traits;

/**
 * Trait FactoryInstanceTrait
 * @package App
 * @method static Route
 */
trait JsonToArrayTrait
{
    public function jsonToArray($data)
    {
        //处理json数组
        foreach ($data as $key => $value) {
            if (!is_array($value) && is_json($value) && !str_ends_with($key, 'id_list')) {
                $value = json_decode($value, true);
                $data[$key] = $value;
            }
        }
        return $data;
    }
}