<?php


namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\BaseService;
use App\Services\CardService;
use App\Services\FileService;


/**
 * Class AddressController
 * @package App\Http\Controllers\Admin
 * @property CardService $service
 */
class CardController extends BaseController
{
    public function __construct(CardService $service)
    {
        parent::__construct($service);
    }
}