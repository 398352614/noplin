<?php

namespace App\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Trait FactoryInstanceTrait
 * @package App
 * @method static Route
 */
trait FactoryInstanceTrait
{
    /**
     * @param $className
     * @param array $parameters
     * @return mixed
     * @throws BindingResolutionException
     */
    public static function getInstance($className, array $parameters = []): mixed
    {
        app()->singleton($className);
        return app()->make($className, $parameters);
    }
}