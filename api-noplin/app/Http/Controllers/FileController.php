<?php


namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\BaseService;
use App\Services\FileService;


/**
 * Class AddressController
 * @package App\Http\Controllers\Admin
 * @property FileService $service
 */
class FileController extends BaseController
{
    public function __construct(FileService $service)
    {
        parent::__construct($service);
    }

    /**
     * @throws BusinessException
     */
    public function store()
    {
        $this->service->store($this->data);
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
    }
}