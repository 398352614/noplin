<?php

use Illuminate\Support\Arr;

if (!function_exists('array_create_group_index')) {
    /**
     * 根据$field，数组列表进行分组并创建索引
     * @param $arr
     * @param $field
     * @return array
     */
    function array_create_group_index($arr, $field): array
    {
        $newArray = [];
        foreach ($arr as $val) {
            $newArray[$val[$field]][] = $val;
        }
        unset($arr);
        return $newArray;
    }
}

if (!function_exists('array_level')) {
    /**
     * 返回多位数组层数
     * @param array $array
     * @param int $count
     * @return int
     */
    function array_level(array $array = [], int $count = 0): int
    {
        foreach ($array as $value) {
            $count++;
            if (!is_array($value)) {
                return $count;
            } else {
                return array_level($value, $count);
            }
        }
    }
}


if (!function_exists('array_create_index')) {
    /**
     * 根据$field，数组列表创建索引
     * @param $arr
     * @param string $field 属性名称，必须是唯一键
     * @return array
     */
    function array_create_index($arr, string $field): array
    {
        $newArr = [];
        foreach ($arr as $key => $val) {
            $newArr[$val[$field]] = $val;
        }
        unset($arr);
        return $newArr;
    }
}


if (!function_exists('explode_postcode')) {
    /**
     * 提取邮编数字部分与字母部分
     * @param $postcode
     * @param bool $letter
     * @return string|array|null
     */
    function explode_postcode($postcode, bool $letter = false): string|array|null
    {
        $postcode = trim($postcode);
        $final_postcode = preg_replace('/\D/', '', $postcode);
        if ($letter) {
            return trim($postcode, $final_postcode);
        }

        return $final_postcode;
    }
}

if (!function_exists('has_chinese')) {
    /**
     * 字符串是否包含中文
     * @param $str
     * @return bool
     */
    function has_chinese($str): bool
    {
        return preg_match('/[\x{4e00}-\x{9fa5}]/u', $str) === 1;
    }
}


if (!function_exists('explode_id_string')) {
    /**
     * 分隔ID字符串
     * @param string $delimiter
     * @param $str
     * @return array
     */
    function explode_id_string($str, string $delimiter = ','): array
    {
        $list = is_array($str) ? $str : explode($delimiter, $str);
        $list = array_filter($list, function ($value) {
            return is_numeric($value);
        });
        return array_unique($list);
    }
}

if (!function_exists('array_only_fields_sort')) {
    /**
     * 数组排序
     * @param $data
     * @param $fields
     * @return array
     */
    function array_only_fields_sort($data, $fields): array
    {
        $newData = [];
        $params = 2;
        foreach ($fields as $v) {
            if (!array_key_exists($v, $data)) {
                $params = 1;
            }
        }
        if ($params == 1) {
            $newData = Arr::only($data, $fields);
        } else {
            foreach ($fields as $v) {
                $newData[$v] = $data[$v] ?? '';
            }
        }
        return $newData;
    }
}

if (!function_exists('array_key_prefix')) {
    /**
     * 设置数组键的前缀
     * @param $arr
     * @param string $prefix
     * @return array
     */
    function array_key_prefix($arr, string $prefix = ''): array
    {
        foreach ($arr as $key => $value) {
            $arr[$prefix . $key] = $value;
            unset($arr[$key]);
        }
        return $arr;
    }
}

if (!function_exists('have_special_char')) {
    /** 判断是否有表情字符
     * @param $str
     * @return bool
     */
    function have_special_char($str): bool
    {
        $length = mb_strlen($str);
        $array = [];
        for ($i = 0; $i < $length; $i++) {
            $array[] = mb_substr($str, $i, 1, 'utf-8');
            if (strlen($array[$i]) >= 4) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('number_format_simple')) {
    /** 自动转化为金额格式
     * @param $number
     * @param int $decimals
     * @param string $dec_point
     * @param string $thousands_sep
     * @return string
     */
    function number_format_simple($number, int $decimals = 2, string $dec_point = '.', string $thousands_sep = ''): string
    {
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }
}

if (!function_exists('is_json')) {
    /**
     * 判断字符串是否是json
     * @param $str
     * @return bool
     */
    function is_json($str): bool
    {
//        json_decode($str, JSON_UNESCAPED_UNICODE);
//        return (json_last_error() == JSON_ERROR_NONE);
        if (empty($str)) {
            return false;
        }
        $data = json_decode($str);
        if (($data && (is_object($data))) || (is_array($data) && !empty($data))) {
            return true;
        }
        return false;
    }

}
