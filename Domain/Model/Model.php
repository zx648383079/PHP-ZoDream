<?php
declare(strict_types=1);
namespace Domain\Model;
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/3/18
 * Time: 21:41
 */
use Zodream\Database\Model\Model as BaseModel;
use Zodream\Infrastructure\Contracts\Database;


abstract class Model extends BaseModel {

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