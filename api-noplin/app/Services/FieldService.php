<?php


namespace App\Services;

use App\Models\Card;
use App\Resources\AuthResource;



class FieldService extends BaseService
{
    /**
     * AuthService constructor.
     * @param Card $model
     */
    public function __construct(Card $model)
    {
        parent::__construct($model);
    }
}
