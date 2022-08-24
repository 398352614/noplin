<?php


namespace App\Traits;

use Carbon\CarbonInterval;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;

/**
 * Trait FactoryInstanceTrait
 * @package App
 * @method static Route
 */
trait ReadableTrait
{
    use FactoryInstanceTrait;

    /**
     * @param $data
     * @return mixed
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function readable($data): mixed
    {
        if (!empty($data)) {
            $data = $this->transDictionary($data);
            return $this->transDuration($data);
        } else {
            return [];
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function transDictionary($data): mixed
    {
        foreach ($data->dictionary as $field => $array) {
            if (str_ends_with($field, '_list')) {
                $data[$field . '_name'] = '';
                $list = explode(',', $data[$field]);
                $originArray = [];
                foreach ($list as $v) {
                    $originArray[] = $array[$v] ?? $v;
                }
                $data[$field . '_name'] = implode(',', $originArray);
            } else {
                $data[$field . '_name'] = $array[$data[$field]] ?? $data[$field];
            }
        }
        return $data;
    }

    /**
     * 翻译字典
     * @param $data
     * @return array
     */
    public function transDictionaryList($data): array
    {
        foreach ($data as $k => $v) {
            foreach ($v as $x => $y) {
                $data[$k][$x] = __($y) ?? $y;
            }
        }
        return $data;
    }

    /**
     * 翻译时长
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function transDuration($data): mixed
    {
        //临时适配carbon
        $locale = App::getLocale() == "en" ? 'en' : 'zh-CN';
        $array = $data->getAttributes();
        foreach ($array as $k => $v) {
            if (str_ends_with($k, '_duration')) {
                $data[$k . '_human'] = "";
                if (!empty($v)) {
                    $data[$k . '_human'] = CarbonInterval::second($v)->cascade()->locale($locale)->forHumans();
                }
            }
        }
        return $data;
    }

}