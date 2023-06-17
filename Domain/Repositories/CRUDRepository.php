<?php
declare(strict_types=1);
namespace Domain\Repositories;

use Domain\Model\Model;
use Domain\Model\SearchModel;
use Zodream\Database\Contracts\SqlBuilder;

abstract class CRUDRepository {

    protected static array $searchKeys = ['name'];

    abstract protected static function query(): SqlBuilder;
    abstract protected static function createNew(): Model;

    public static function getList(string $keywords = '') {
        return static::query()->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, static::$searchKeys, true, '', $keywords);
            })->page();
    }

    public static function get(int $id) {
        $model = static::query()->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        return $model;
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? static::query()->where('id', $id)->first() : static::createNew();
        if (empty($model)) {
            throw new \Exception(__('id is error'));
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        static::afterSave($model->id, $data);
        static::updateCache();
        return $model;
    }

    protected static function afterSave(int $id, array $data) {

    }

    public static function remove(int $id) {
        $model = static::query()->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception(__('delete is error'));
        }
        if (!static::removeWith($id)) {
            throw new \Exception(__('delete is error'));
        }
        $model->delete();
        static::updateCache();
    }

    protected static function removeWith(int $id): bool {
        return true;
    }

    protected static function updateCache() {
        cache()->delete('shop_category_tree');
        cache()->delete('shop_category_level');
    }
}