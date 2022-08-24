<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Models\BaseModel;
use App\Resources\BaseResource;
use App\Traits\JsonToArrayTrait;
use App\Traits\ReadableTrait;
use App\Traits\SearchTrait;
use App\Traits\ServiceTrait;
use App\Validation\BaseValidation;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

/**
 * Class BaseService
 * @package App\Services
 * @property BaseResource $resource
 */
class BaseService
{
    use SearchTrait, JsonToArrayTrait, ReadableTrait, ServiceTrait;

    protected Builder $query;
    protected mixed $data;
    protected BaseValidation $validation;
    protected string $resource;

    public function __construct(
        protected BaseModel $model,
        $resource = null,
    )
    {
        $this->resource = $resource ?? BaseResource::class;
        $this->query = $this->model::query();
        $this->data = $this->jsonToArray(request()->all());
        $this->model->dictionary = $this->transDictionaryList($this->model->dictionary);
    }

    //处理
    public function deal($id, $data)
    {

    }

    /**
     * 查询
     * @param array $where
     * @param array|string[] $only
     * @param bool $resource
     * @param array $groupBy
     * @param array|null $orderBy
     * @param array|null $orderByDesc
     * @param bool $page
     * @param bool $readable
     * @return array|AnonymousResourceCollection|Collection
     * @throws BusinessException|BindingResolutionException
     */
    public function getList(
        array $where = [],
        array $only = ['*'],
        bool $resource = false,
        array $groupBy = [],
        array $orderBy = null,
        array $orderByDesc = null,
        bool $page = false,
        bool $readable = true
    ): array|AnonymousResourceCollection|Collection
    {
        if (!empty($where)) {
            $this->buildQuery($this->query, $where);
        }
        if (!empty($groupBy)) {
            $this->query->groupBy($groupBy);
        }
        if (!empty($orderBy)) {
            foreach ($orderBy as $v) {
                $this->query->orderBy($v);
            }
        }
        if (!empty($orderByDesc)) {
            foreach ($orderByDesc as $v) {
                $this->query->orderByDesc($v);
            }
        }
        //如果per_page为0，则不分页
        if ($page) {
            if (array_key_exists('per_page', $this->data) && $this->data['per_page'] == 0) {
                $data = $this->resource::collection($this->query->get($only));
            } else {
                $data = $this->resource::collection($this->getPaginate());
            }
        } elseif ($resource) {
            $data = $this->resource::collection($this->query->get($only));
        } else {
            $data = $this->query->get($only);
        }
        //翻译
        if ($readable == true) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->readable($v);
            }
        }
        return $data;
    }

    /**
     * 查看
     * @param array $where
     * @param string[] $only
     * @param false $resource
     * @param bool $lock
     * @param bool $readable
     * @param bool $throw
     * @param bool $toArray
     * @param array $orderBy
     * @param array $orderByDesc
     * @return mixed
     * @throws BindingResolutionException
     * @throws BusinessException
     */
    public function getInfo(
        mixed $where = [],
        array $only = ['*'],
        bool $resource = false,
        bool $lock = false,
        bool $readable = true,
        bool $throw = true,
        bool $toArray = true,
        array $orderBy = [],
        array $orderByDesc = [],
    ): mixed
    {
        $where = $this->byId($where);
        if (!empty($where)) {
            $this->buildQuery($this->query, $where);
        }
        if (!empty($orderBy)) {
            foreach ($orderBy as $v) {
                $this->query->orderBy($v);
            }
        }
        if (!empty($orderByDesc)) {
            foreach ($orderByDesc as $v) {
                $this->query->orderByDesc($v);
            }
        }
        $data = $this->query->first($only);
        if (!empty($data)) {
            if ($resource) {
                $data = new $this->resource($data);
            }
            //如果锁编号，不能读不能更新，否则能读不能更新
            //加锁不是为了此操作，而是为了此后的操作
//            if ($lock) {
//                if ($this->model instanceof Number) {
//                    $this->query->lockForUpdate();
//                } else {
            $this->query->sharedLock();
//                }
//            }
            //翻译字典
            if ($readable == true) {
                $this->readable($data);
            }
            if ($toArray == true) {
                $data = $data->getAttributes();
            }
            $this->query = $this->model::query();
            return $data;
        } elseif ($throw) {
            throw new BusinessException('数据不存在');
        } else {
            return [];
        }
    }

    /**
     * 新增
     * @param $data
     * @param bool|string $returnId
     * @return Model|int
     */
    public function create(
        $data,
        bool $returnId = false,
    ): Model|int
    {
        $fields = $this->model->getFillable();
        if (is_int(array_keys($data)[0])) {
            //批量新增
            foreach ($data as $k => $v) {
                $data[$k] = Arr::only($v, $fields);
                $data[$k]['created_at'] = $data[$k]['updated_at'] = Carbon::now();
            }
            $result = $this->query->insert($data);
        } else {
            //单条新增
            $data = Arr::only($data, $fields);
            $data['created_at'] = $data['updated_at'] = Carbon::now();
            if ($returnId == true) {
                $result = $this->query->insertGetId($data);
            } else {
                $result = $this->query->insert($data);
            }
        }
        return $result;
    }

    /**
     * 修改
     * @param $where
     * @param $data
     * @param bool $rebuild
     * @param bool $throw
     * @return void
     * @throws BusinessException
     */
    public function modify($where, $data, bool $rebuild = false, bool $throw = false)
    {
        $fields = $this->model->getFillable();
        $where = $this->byId($where);
        if (empty($where)) {
            throw new BusinessException('不能修改所有');
        }
        $this->buildQuery($this->query, (array)$where);
        if ($rebuild) {
            $this->query->delete();
            $row = $this->create($data);
        } else {
            $row = $this->query->update(Arr::only($data, $fields));
        }
        if ($throw == true && $row == false) {
            throw new BusinessException('重建失败');
        }
    }

    /**
     * 删除
     * @param $where
     * @param bool $soft
     * @return mixed
     * @throws BusinessException
     */
    public function remove($where, bool $soft = false): mixed
    {
        $where = $this->byId($where);
        if (empty($where)) {
            throw new BusinessException('不能删除所有');
        }
        $this->buildQuery($this->query, $where);
        if ($soft == true) {
            return $this->query->delete();
        }
        return $this->query->forceDelete();
    }

    /**
     * 按条件计数
     * @param array $where
     * @return int
     */
    public function countWhere(array $where = []): int
    {
        $this->query = $this->model::query();
        if (!empty($where)) {
            $this->buildQuery($this->query, $where);
        }
        $count = $this->query->count();
        return $count ?? 0;
    }

    /**
     * 启用/禁用
     * @param $id
     * @param int $status
     * @throws BusinessException
     */
    public function switch($id, int $status = 1)
    {
        self::modify($id, ['status' => $status]);
    }

    /**
     * 验证并整理格式
     * @param $data
     * @param null $id
     */
    public function check(&$data, $id = null)
    {
        //占位
    }

    /**
     * 事务
     * @param $method
     * @param array $param
     * @return mixed
     * @throws BusinessException
     */
    public function transaction($method, array $param): mixed
    {
        try {
            // 开启事物
            DB::beginTransaction();
            if (!method_exists($this, $method)) {
                throw new BusinessException($method . '方法未定义',);
            }
            $return = call_user_func_array([$this, $method], $param);
            // 提交事物
            DB::commit();
        } catch (BusinessException | Exception $e) {
            // 回滚事物
            DB::rollBack();
            throw new BusinessException($e->getMessage(), $e->getCode(), $e->replace, $e->data);
        }
        return $return;
    }

    /**
     * 增删改套事务
     * @param $method
     * @param $arguments
     * @return false|mixed
     * @throws Exception
     */
    public function __call($method, $arguments)
    {
        // show/get/query/find 表示只读事物
        $pattern = '/^(show\w*)$|^(get\w*)$|^(select\w*)$|^(query\w*)$|^(find\w*)$|^(export\w*)|^(index\w*)$/';
        preg_match($pattern, $method, $match);
        // 匹配上了，就直接执行只读方法
        if (is_array($match) && count($match)) {
            $return = call_user_func_array([$this, $method], !empty($arguments) ? $arguments : []);
        } else {
            // 没有匹配上，则加上事物执行
            $return = $this->transaction($method, !empty($arguments) ? $arguments : []);
        }
        return $return;
    }
}
