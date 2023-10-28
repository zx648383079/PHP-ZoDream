<?php
declare(strict_types=1);
namespace Domain\Model;

use IteratorAggregate;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Database\Relation;
use Zodream\Helpers\Json;
use Zodream\Html\Page;

class ModelHelper {
    /**
     * 从值获取数字数组
     * @param string|array $selected 字符串可以json或者以,分割的
     * @return int[]
     */
    public static function parseArrInt(mixed $selected): array {
        if (!empty($selected) && is_string($selected)) {
            if (!str_contains($selected, '[') && !str_contains($selected, '{')) {
                $selected = explode(',', $selected);
            } else {
                $selected = Json::decode($selected, true);
            }
        }
        $data = [];
        foreach ((array)$selected as $item) {
            $item = intval($item);
            if ($item < 1) {
                continue;
            }
            $data[] = $item;
        }
        return $data;
    }

    /**
     * 拆分两个数组
     * @param array $current
     * @param array $exist
     * @param bool $intersect 是否要真的获取共有的
     * @param bool $removeEmpty 是否移除空的项
     * @return array 返回 [新增, 共有, 删除]
     */
    public static function splitId(
        array $current, array $exist, bool $intersect = false, bool $removeEmpty = true): array {
        if ($removeEmpty) {
            $current = static::removeEmpty($current);
            $exist = static::removeEmpty($exist);
        }
        if (empty($exist) && empty($current)) {
            return [[], [], []];
        }
        if (empty($exist)) {
            return [$current, [], []];
        }
        if (empty($current)) {
            return [[], [], $exist];
        }
        return [array_diff($current, $exist), $intersect ? array_intersect($current, $exist) : [], array_diff($exist, $current)];
    }

    private static function removeEmpty(array $data): array {
        if (empty($data)) {
            return [];
        }
        $items = [];
        foreach ($data as $val) {
            if (empty($val) || in_array($val, $items)) {
                continue;
            }
            $items[] = $val;
        }
        return $items;
    }

    /**
     * 将多项表单转为数组
     * @param array $data
     * @param null $default
     * @param callable|null $check
     * @return array
     */
    public static function formArr(array $data, $default = null, callable $check = null): array {
        if (empty($data)) {
            return [];
        }
        $items = [];
        $first = current($data);
        if (!is_array($first)) {
            return [];
        }
        foreach ($first as $i => $_) {
            $item = [];
            foreach ($data as $key => $args) {
                $item[$key] = $args[$i] ?? $default;
            }
            if (!empty($check) && call_user_func($check, $item) === false) {
                continue;
            }
            $items[] = $item;
        }
        return $items;
    }

    public static function updateField(Model $model, array $checkMap, array $data): Model {
        foreach ($data as $action => $val) {
            if (is_int($action)) {
                if (empty($val)) {
                    continue;
                }
                list($action, $val) = [$val, $model->{$val} > 0 ? 0 : 1];
            }
            if (empty($action) || !in_array($action, $checkMap)) {
                continue;
            }
            $model->{$action} = $val;
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    /**
     * 获取关联数据的统计数据
     * @param array|Page $items
     * @param SqlBuilder $builder
     * @param string $dataKey
     * @param string $foreignKey
     * @param string $countKey
     * @return array|IteratorAggregate
     */
    public static function bindCount(array|IteratorAggregate $items, SqlBuilder $builder, string $dataKey,
                                     string $foreignKey, string $countKey = 'data_count'): array|IteratorAggregate {
        $keyItems = Relation::columns($items, $dataKey);
        if (empty($keyItems)) {
            return $items;
        }
        $results =  $builder->groupBy($foreignKey)
            ->selectRaw('COUNT(*) as count')
            ->select($foreignKey)->asArray()->pluck('count', $foreignKey);
        foreach ($items as &$item) {
            $item[$countKey] = isset($results[$item[$dataKey]]) ? intval($results[$item[$dataKey]]) : 0;
        }
        unset($item);
        return $items;
    }

    /**
     * 绑定下一级
     * @param array|Page $items
     * @param array $relations [key => {query: Sql, link:array}]
     * @param string $childrenKey
     * @return array|Page
     */
    public static function bindTwoRelation(array|IteratorAggregate $items,
                                           array $relations, string $childrenKey = 'children'): array|IteratorAggregate {
        static::eachTwoLevel($items, function ($item) use (&$relations) {
            foreach ($relations as $key => $relation) {
                if (empty($relation['link'])) {
                    unset($relations[$key]);
                    continue;
                }
                if (!isset($relation['linkData'])) {
                    $relation['linkData'] = [];
                }
                foreach ($relation['link'] as $k => $_) {
                    if ($item[$k] && !in_array($item[$k], $relation['linkData'])) {
                        $relation['linkData'][] = $item[$k];
                    }
                }
                $relations[$key] = $relation;
            }
        }, $childrenKey);
        $hasData = false;
        foreach ($relations as $key => $relation) {
            if (empty($relation['linkData'])) {
                continue;
            }
            /** @var SqlBuilder $query */
            $query = $relation['query'];
            $relation['dataKey'] = key($relation['link']);
            $relation['linkKey'] = $relation['link'][$relation['dataKey']];
            $query->whereIn($relation['linkKey'], $relation['linkData']);
            $relation['items'] = $query->pluck(null, $relation['linkKey']);
            if (!$hasData && !empty($relation['items'])) {
                $hasData = true;
            }
            $relations[$key] = $relation;
        }
        if (!$hasData) {
            return $items;
        }
        $bindingFn = function ($item) use ($relations) {
            foreach ($relations as $key => $relation) {
                if (empty($relation['items'])) {
                    continue;
                }
                $val = $item[$relation['dataKey']];
                $item[$key] = !empty($val) && !empty($relation['items'][$val]) ?
                    $relation['items'][$val] : null;
            }
            return $item;
        };
        foreach ($items as &$item) {
            $item = call_user_func($bindingFn, $item);
            if (!$item[$childrenKey]) {
                continue;
            }
            foreach ($item[$childrenKey] as $i => $it) {
                $item[$childrenKey][$i] = call_user_func($bindingFn, $it);
            }
        }
        unset($item);
        return $items;
    }

    protected static function eachTwoLevel(array|IteratorAggregate $items,
                                          callable $cb,
                                          string $childrenKey = 'children'): void {
        foreach ($items as $item) {
            call_user_func($cb, $item);
            if (!$item[$childrenKey]) {
                continue;
            }
            foreach ($item[$childrenKey] as $it) {
                call_user_func($cb, $it);
            }
        }
    }
}