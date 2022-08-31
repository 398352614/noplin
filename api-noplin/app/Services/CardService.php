<?php


namespace App\Services;

use App\Models\Card;
use App\Resources\AuthResource;
use App\Resources\CardResource;


class CardService extends BaseService
{
    /**
     * AuthService constructor.
     * @param Card $model
     */
    public function __construct(Card $model)
    {
        parent::__construct($model,CardResource::class);
    }
}
