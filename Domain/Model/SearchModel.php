<?php
declare(strict_types=1);
namespace Domain\Model;

use Zodream\Database\Contracts\SqlBuilder;

class SearchModel {
    /**
     * 生成搜索查询语句
     * @param SqlBuilder $query
     * @param string|array $columns
     * @param bool $saveLog
     * @param string $key
     * @param string $value
     * @return SqlBuilder
     * @throws \Exception
     */
    public static function search(SqlBuilder $query, string|array $columns, bool $saveLog = true,
                                  string $key = 'keywords', string $value = '') {
        $columns = (array)$columns;
        $keywords = explode(' ', empty($key) ? $value : request()->get($key));
        foreach ($keywords as $item) {
            $item = trim(trim($item), '%');
            if (empty($item)) {
                continue;
            }
            foreach ($columns as $column) {
                $query->orWhere($column, 'like', '%'.$item.'%');
            }
            if (!$saveLog) {
                continue;
            }
            $item = trim(str_replace('%', '', $item));
            if (empty($item)) {
                continue;
            }
//            static::create([
//                'keyword' => $item,
//                'count' => 1,
//                'created_at' => date('Y-m-d')
//            ]);
        }
        return $query;
    }

    /**
     * 查询语句并用（） 包起来
     * @param SqlBuilder $query
     * @param string|array $columns
     * @param bool $saveLog
     * @param string $key
     * @param string $value
     * @return SqlBuilder
     */
    public static function searchWhere(SqlBuilder $query, string|array $columns, bool $saveLog = true,
                                       string $key = 'keywords', string $value = '') {
        return $query->where(function ($query) use ($columns, $saveLog, $key, $value) {
            static::search($query, $columns, $saveLog, $key, $value);
        });
    }

    /**
     * 过滤sort order
     * @param $sort
     * @param $order
     * @param array $sort_list 允许的sort值
     * @param string $default_order
     * @return array
     */
    public static function checkSortOrder(string $sort, bool|int|string $order,
                                          array $sort_list, string $default_order = 'desc') {
        if (is_bool($order)) {
            $order = $order ? 'desc' : 'asc';
        } elseif (is_numeric($order)) {
            $order = $order > 0 ? 'desc' : 'asc';
        } elseif (!in_array(strtolower($order), ['desc', 'asc'])) {
            $order = $default_order;
        }
        if (!in_array($sort, $sort_list)) {
            $sort = reset($sort_list);
        }
        return [$sort, $order];
    }

    /**
     * 根据搜索进行显示
     * @param SqlBuilder $query
     * @param string|array $columns
     * @param string $keywords
     * @param array $queries 这是主键
     * @return array
     */
    public static function searchOption(SqlBuilder $query, string|array $columns,
                                        string $keywords = '', array $queries = []): array {
        $perPage = static::getPerPage($queries);
        if ($perPage < 1) {
            return [];
        }
        if (!empty($keywords)) {
            $query->where(function ($query) use ($columns, $keywords) {
                static::search($query, $columns, false, '', $keywords);
            });
        }
        foreach ($queries as $key => $value) {
            if (!is_array($value) && empty($value)) {
                return [];
            }
            if (is_array($value)) {
                $query->whereIn($key, $value);
                continue;
            }
            $query->where($key, $value);
        }
        return $query->limit($perPage)->get();
    }

    private static function getPerPage(array $queries = [], int $default = 20): int {
        $perPage = intval(request()->get('per_page'));
        if ($perPage > 0) {
            return $perPage;
        }
        if (empty($queries) || count($queries) > 1) {
            return $default;
        }
        $values = current($queries);
        return is_array($values) ? count($values) : 1;
    }
}