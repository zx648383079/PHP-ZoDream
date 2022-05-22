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
            throw new \Exception('数据有误');
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        static::query()->where('id', $id)->delete();
    }
}