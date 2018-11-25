<?php
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


abstract class Model extends BaseModel {
    /**
     * 生成搜索查询语句
     * @param Builder $query
     * @param $columns
     * @param bool $saveLog
     * @param string $key
     * @return Builder
     * @throws \Exception
     */
    public static function search($query, $columns, $saveLog = true, $key = 'keywords') {
        $columns = (array)$columns;
        $keywords = explode(' ', app('request')->get($key));
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
     * 更新自增字段
     * @param callable $cb
     * @param string $key
     * @throws \Exception
     */
    public static function refreshPk(callable $cb, $key = 'id') {
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
        Command::getInstance()->execute(sprintf('ALTER TABLE %s AUTO_INCREMENT = %s;',
            Command::getInstance()->addPrefix(static::tableName()), $i));
    }
}