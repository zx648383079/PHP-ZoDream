<?php
namespace Domain\Model;
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/3/18
 * Time: 21:41
 */
use Zodream\Database\Model\Model as BaseModel;
use Zodream\Database\Query\Query;
use Zodream\Infrastructure\Http\Request;

abstract class Model extends BaseModel {
    /**
     * 生成搜索查询语句
     * @param Query $query
     * @param $columns
     * @param bool $saveLog
     * @param string $key
     * @return Query
     */
    public static function search($query, $columns, $saveLog = true, $key = 'keywords') {
        $columns = (array)$columns;
        $keywords = explode(' ', Request::request($key));
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
}