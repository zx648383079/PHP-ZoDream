<?php
declare(strict_types=1);
namespace Domain\Model;
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/3/18
 * Time: 21:41
 */
use Zodream\Database\Command;
use Zodream\Database\Model\Model as BaseModel;
use Zodream\Database\Query\Builder;
use Zodream\Infrastructure\Contracts\Database;


abstract class Model extends BaseModel {
    /**
     * 生成搜索查询语句
     * @param Builder $query
     * @param string|array $columns
     * @param bool $saveLog
     * @param string $key
     * @param string $value
     * @return Builder
     * @throws \Exception
     */
    public static function search(Builder $query, string|array $columns, bool $saveLog = true,
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
     * @param Builder $query
     * @param string|array $columns
     * @param bool $saveLog
     * @param string $key
     * @param string $value
     * @return Builder
     */
    public static function searchWhere(Builder $query, string|array $columns, bool $saveLog = true,
                                       string $key = 'keywords', string $value = '') {
        return $query->where(function ($query) use ($columns, $saveLog, $key) {
            static::search($query, $columns, $saveLog, $key);
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
     * 更新自增字段
     * @param callable $cb
     * @param string $key
     * @throws \Exception
     */
    public static function refreshPk(callable $cb, string $key = 'id') {
        $data = static::orderBy($key, 'asc')->pluck($key);
        $i = 1;
        foreach ($data as $id) {
            if ($id == $i) {
                $i ++;
                continue;
            }
            static::where('id', $id)->update([
                'id' => $i
            ]);
            call_user_func($cb, $id, $i);
            $i ++;
        }
        /** @var Database $db */
        $db = app('db');
        $db->execute(sprintf('ALTER TABLE %s AUTO_INCREMENT = %s;',
            $db->addPrefix(static::tableName()), $i));
    }

}