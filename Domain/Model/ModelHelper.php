<?php
declare(strict_types=1);
namespace Domain\Model;

use Zodream\Helpers\Json;

class ModelHelper {
    /**
     * 从值获取数字数组
     * @param string|array $selected 字符串可以json或者以,分割的
     * @return int[]
     */
    public static function parseArrInt($selected): array {
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
                $item[$key] = isset($args[$i]) ? $args[$i] : $default;
            }
            if (!empty($check) && call_user_func($check, $item) === false) {
                continue;
            }
            $items[] = $item;
        }
        return $items;
    }
}