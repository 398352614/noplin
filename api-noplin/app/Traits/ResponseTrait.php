<?php


namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

trait ResponseTrait
{
    /**
     * @param int $code
     * @param mixed $data
     * @param string $msg
     * @param array $replace
     * @return array
     */
    public function responseFormat(int $code = 200, mixed $data = [], string $msg = 'successful', array $replace = []): array
    {
        if (!empty($data->links)) {
            return [
                'code' => $code,
                'data' => $data->data ?? [],
                'links' => $data->links,
                'meta' => Arr::except(collect($data->meta)->toArray(), ['links']),
                'msg' => !empty($replace) || (App::getLocale() != 'cn') ? __($msg, $replace) : $msg
            ];
        } elseif (!empty($data) &&
            (is_array($data) && array_key_exists('data', $data) || is_object($data) && property_exists($data, 'data'))
        ) {
            return [
                'code' => $code,
                'data' => $data->data ?? [],
                'msg' => !empty($replace) || (App::getLocale() != 'cn') ? __($msg, $replace) : $msg
            ];
        } else {
            return [
                'code' => $code,
                'data' => $data ?? [],
                'msg' => !empty($replace) || (App::getLocale() != 'cn') ? __($msg, $replace) : $msg
            ];
        }
    }
}
