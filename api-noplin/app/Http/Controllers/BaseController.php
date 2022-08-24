<?php

namespace App\Http\Controllers;

use App\Exceptions\BusinessException;
use App\Services\BaseService;
use App\Traits\JsonToArrayTrait;
use Exception;
use Illuminate\Support\Facades\Request;

class BaseController extends Controller
{
    use JsonToArrayTrait;

    public array $data;

    public BaseService $service;

    /**
     * BaseController constructor.
     * @param BaseService $service
     */
    public function __construct(BaseService $service)
    {
        $this->service = $service;
        $this->data = $this->jsonToArray(Request::all());
    }

    /**
     * 查询
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->service->search();
    }

    /**
     * 查看
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function show($id): mixed
    {
        return $this->service->getInfo($id);
    }

    /**
     * 新增
     */
    public function store()
    {
        $this->service->check($this->data);
        $this->service->create($this->data);
    }

    /**
     * 修改
     * @param $id
     * @throws BusinessException
     */
    public function edit($id)
    {
        $this->service->check($this->data, $id);
        $this->service->modify($id, $this->data);
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessException
     */
    public function destroy($id)
    {
        $this->service->remove($id);
    }

    /**
     * 开闭
     * @param $id
     * @throws BusinessException
     */
    public function switch($id)
    {
        $this->service->switch($id, $this->data['status']);
    }

    /**
     * 处理
     * @param $id
     */
    public function deal($id)
    {
        $this->service->deal($id, $this->data['status']);
    }

}