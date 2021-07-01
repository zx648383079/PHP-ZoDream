<?php
declare(strict_types=1);
namespace Domain\Concerns;

use Zodream\Helpers\Json;

trait TableMeta {

//    protected static string $idKey = 'item_id';
//    protected static array $defaultItems = [];

    /**
     * 获取并合并默认的
     * @param int $id
     * @return array
     */
    public static function getOrDefault(int $id) {
        return static::getMap($id, static::$defaultItems);
    }

    /**
     * 获取
     * @param int $id
     * @param array $default
     * @return array
     */
    public static function getMap(int $id, array $default = []) {
        if ($id < 1) {
            return $default;
        }
        $items = static::query()->where(static::$idKey, $id)->pluck('content', 'name');
        return array_merge($default, $items);
    }

    /**
     * 批量保存
     * @param int $id
     * @param array $data
     */
    public static function saveBatch(int $id, array $data) {
        if (empty($data)) {
            return;
        }
        $metaKeys = array_keys(static::$defaultItems);
        $items = static::get($id);
        $add = [];
        foreach ($data as $name => $content) {
            if (!in_array($name, $metaKeys)) {
                continue;
            }
            if (is_null($content)) {
                $content = '';
            } elseif (is_array($content)) {
                $content = Json::encode($content);
            }
            if (!array_key_exists($name, $items)) {
                if (empty($content)) {
                    continue;
                }
                $add[] = [
                    static::$idKey => $id,
                    'name' => $name,
                    'content' => $content
                ];
                continue;
            }
            if ($content === $items[$name]) {
                continue;
            }
            static::query()->where(static::$idKey, $id)->where('name', $name)->update([
                'content' => $content
            ]);
        }
        if (empty($add)) {
            return;
        }
        static::query()->insert($add);
    }

    /**
     * 根据主id删除meta
     * @param int $id
     * @return mixed
     */
    public static function deleteBatch(int $id) {
        return static::query()->where(static::$idKey, $id)->delete();
    }
}