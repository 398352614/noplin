<?php

namespace App\Traits;

use App\Constants\Constant;
use App\Constants\ErrorCode;
use App\Exceptions\BusinessException;
use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\Pure;

/**
 * Trait SearchTrait
 * @package App\Traits
 * @property Builder $query
 * @property BaseModel $model
 */
trait SearchTrait
{

    /**
     * 搜索
     * @return mixed
     */
    public function search(): mixed
    {
        if (!empty($this->data['trash'])) {
            if ($this->data['trash'] == Constant::ONLY_TRASH) {
                $this->query = $this->model::onlyTrashed()->newQuery();
            } elseif ($this->data['trash'] == Constant::WITH_TRASH) {
                $this->query = $this->model::withTrashed()->newQuery();
            }
        }
        $filterBy = $this->getFilterBy();
        $orderBy = $this->getOrderBy();
        $orderByDesc = $this->getOrderByDesc();
        return $this->getList(
            where: $filterBy,
            resource: true,
            orderBy: $orderBy,
            orderByDesc: $orderByDesc,
            page: true,
            readable: true
        );
    }

    /**
     * 查询条件
     * @param Builder $query
     * @param array $conditions
     */
    public function buildQuery(Builder $query, array $conditions)
    {
        foreach ($conditions as $k => $v) {
            $type = '=';
            $value = $v;
            if (is_array($v) && $k != 'andQuery') {
                [$type, $value] = $v;
            }
            if (!is_array($value)) {
                if (!empty($value)) {
                    $value = trim($value);
                } else {
                    $value = '';
                }
            }
            //如果是like搜索，但是值为空，跳过
            if ($type === 'like' && $value === '') {
                continue;
            }
            //如果是like查询，但其中包含Mysql不能识别的%和_则加上转义符号
            if ($type === 'like') {
                $value = str_replace('_', '\_', $value);
                $value = str_replace('%', '\%', $value);
            }
            //in
            if ($type === 'in' && is_array($value)) {
                $query->whereIn($k, $value);
                continue;
            }
            if ($type === '<>') {
                $query->where($k, $type, $value);
                continue;
            }
            if ($type === 'all') {
                $query->whereRaw("IFNULL({$k},0) <> -1");
                continue;
            }
            //not in
            if ($type === 'not in' && is_array($value)) {
                $query->whereNotIn($k, $value);
                continue;
            }
            //日期
            if ($type === 'date') {
                $query->whereDate($k, $value);
                continue;
            }
            if ($type === 'month') {
                $query->whereMonth($k, $value);
                continue;
            }
            if ($type === 'day') {
                $query->whereDay($k, $value);
                continue;
            }
            if ($type === 'year') {
                $query->whereYear($k, $value);
                continue;
            }
            if ($type === 'time') {
                $query->whereTime($k, $value);
                continue;
            }
            //如果是between， 按时间过滤
            if ($type === 'between' && is_array($value)) {
                if (empty($value[0]) || empty($value[1])) continue;
                //关联表
                if (strpos($k, ':')) {
                    $k = explode(':', $k);
                    $query->whereHas($k[0], function ($query) use ($k, $value) {
                        if (strpos($value[0], '-') && strpos($value[1], '-')) {
                            $query->whereBetween($k[1], [
                                Carbon::parse($value[0])->startOfDay(),
                                Carbon::parse($value[1])->endOfDay(),
                            ]);
                        } else {
                            $query->whereBetween($k[1], [$value[0], $value[1]]);
                        }
                    });
                    continue;
                }
                //主表过滤
                if (strpos($value[0], '-') && strpos($value[1], '-')) {
                    $query->whereBetween($k, [
                        Carbon::parse($value[0])->startOfDay(),
                        Carbon::parse($value[1])->endOfDay(),
                    ]);
                } else {
                    $query->whereBetween($k, [$value[0], $value[1]]);
                }
                //如果是多个字段联合搜索
            } elseif (strpos($k, ',')) {
                if (strpos($k, ':')) {
                    $k = explode(':', $k);
                    $query->whereHas($k[0], function ($q) use ($k, $value) {
                        $q->where(function ($query) use ($k, $value) {
                            foreach (explode(',', $k[1]) as $item) {
                                $query->orWhere($item, 'like binary', "%{$value}%");
                            }
                        });
                    });
                    continue;
                } else {
                    $query->where(function ($q) use ($k, $value) {
                        foreach (explode(',', $k) as $item) {
                            $q->orWhere($item, 'like binary', "%{$value}%");
                        }
                    });
                }
            } else { //普通类型
                if ($value === '')
                    continue;
                $query->where($k, $type, $type === 'like' ? "%{$value}%" : $value);
            }
        }
    }

    /**
     * 获取分页结果
     * @throws BusinessException
     */
    public function getPaginate(): Paginator
    {
        $this->validatePaginate();
        return $this->query->paginate($this->data['per_page'] ?? 10);
    }


    /**
     * 验证分页参数
     * @throws BusinessException
     */
    protected function validatePaginate()
    {
        $validator = Validator::make(
            $this->data,
            [
                'page' => 'integer',
                'per_page' => 'integer|in:0,5,10,20,40,50,100,200,1000',
            ],
        );
        if ($validator->fails()) {
            $messageList = Arr::flatten($validator->errors()->getMessages());
            throw new BusinessException(implode(';', $messageList), ErrorCode::VALIDATE_ERROR);
        }
    }

    /**
     * 获取查询条件
     * @return array
     */
    public function getFilterBy(): array
    {
        $data = [];
        foreach ($this->model->filer as $k => $v) {
            if (Arr::has($this->data, $v[1])) {
                //获取操作符
                $data[$k][0] = $v[0];
                //获取值
                if (is_array($v[1])) {
                    $data[$k][1] = [];
                    foreach ($v[1] as $v1) {
                        array_push($data[$k][1], $this->data[$v1]);
                    }
                } else {
                    $data[$k][1] = $this->data[$v[1]];
                }
            }
        }
        return $data;
    }

    /**
     * 获取升序条件
     * @return array
     */
    #[Pure] public function getOrderBy(): array
    {
        $data = [];
        if (!empty($this->data['asc'])) {
            $fillAble = $this->model->getFillable();
            $orderBy = explode(',', $this->data['asc']);
            foreach ($orderBy as $v) {
                if (in_array($v, $fillAble)) {
                    $data[] = $v;
                }
            }
        }
        return $data;
    }

    /**
     * 获取降序条件
     * @return array
     */
    #[Pure] public function getOrderByDesc(): array
    {
        $data = ['id'];
        if (!empty($this->data['desc'])) {
            $fillAble = $this->model->getFillable();
            $orderBy = explode(',', $this->data['desc']);
            foreach ($orderBy as $v) {
                if (in_array($v, $fillAble)) {
                    $data[] = $v;
                }
            }
        }
        return $data;
    }


    /**
     * 如果查询条件是数字，则自动转为ID查询
     * @param $where
     * @return array
     */
    public function byId($where): array
    {
        if (!empty($where) && !is_array($where)) {
            return ['id' => $where];
        }
        if (!empty($where['trash'])) {
            $where['trash'] == Constant::ONLY_TRASH && $this->query = $this->model::onlyTrashed()->newQuery();
            $where['trash'] == Constant::WITH_TRASH && $this->query = $this->model::withTrashed()->newQuery();
        }
        return Arr::only($where, ['id', ...$this->model->getFillable()]);
    }
}
