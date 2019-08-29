<?php
namespace Module\Blog\Domain\Repositories;


use Module\Blog\Domain\Model\TermModel;

class TermRepository {

    /**
     * @var bool|TermModel[]
     */
    private static $caches = false;

    private static function boot() {
        if (static::$caches !== false) {
            return;
        }
        static::$caches = [];
        $data = TermModel::query()->get();
        foreach ($data as $item) {
            static::$caches[intval($item['id'])] = $item;
        }
    }

    /**
     * @param int $id
     * @return TermModel[]|TermModel|null
     */
    public static function get($id = -1) {
        static::boot();
        if (!is_integer($id)) {
            $id = intval($id);
        }
        if ($id < 0) {
            return array_values(static::$caches);
        }
        if ($id < 1 || !isset(static::$caches[$id])) {
            return null;
        }
        return static::$caches[$id];
    }
}