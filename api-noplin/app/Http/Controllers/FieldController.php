<?php


namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\BaseService;
use App\Services\CardService;
use App\Services\FieldService;
use App\Services\FileService;


/**
 * Class AddressController
 * @package App\Http\Controllers\Admin
 * @property FieldService $service
 */
class FieldController extends BaseController
{
    public function __construct(FieldService $service)
    {
        parent::__construct($service);
    }
}