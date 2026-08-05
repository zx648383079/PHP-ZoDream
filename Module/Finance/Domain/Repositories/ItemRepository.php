<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Finance\Domain\Entities\ItemEntity;
use Module\Finance\Domain\Entities\LogEntity;

final class ItemRepository {
    public static function getList(string $keywords = '') {
        $items = ItemEntity::auth()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['remark', 'name']);
        })->orderBy('status', 'asc')->orderBy('id', 'desc')->asArray()->page();
        $now = time();

        $items->map(function(array $item) use ($now) {
            $item['price'] = floatval($item['price']);
            $data['days'] = ceil((($item['deleted_at'] > 0 ? $item['deleted_at'] : $now) - $item['created_at']) / 86400);
            $data['avg'] = round($item['price'] / $data['days'], 2);
            return $data;
        });
        return $items;
    }

    public static function subtotal(): array {
        $total = ItemEntity::auth()->sum('price');
        $avg = 0;
        $now = time();
        ItemEntity::auth()->select('price', 'created_at')->each(function(array $item) use ($now, &$avg) {
            $avg += floatval($item['price']) / ceil(($now - $item['created_at']) / 86400);
        });
        return compact('total', 'avg');
    }

    public static function save(array $data) {
        if (isset($data['id']) && $data['id'] > 0) {
            $model = ItemEntity::auth()->where('id', $data['id'])->first();
            if (empty($model)) {
                throw new Exception('不存在');
            }
        } else {
            $model = new ItemEntity();
        }
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function binding(int $id, int $log) {
        LogEntity::auth()->where('id', $log)->where('type', LogEntity::TYPE_EXPENDITURE)->update([
            'item_id' => $id
        ]);
        static::refresh($id);
    }

    public static function unbinding(int $id, int $log) {
        LogEntity::auth()->where('id', $log)->where('type', LogEntity::TYPE_EXPENDITURE)->update([
            'item_id' => 0
        ]);
        static::refresh($id);
    }

    public static function refresh(int $id) {
        $price = LogEntity::auth()->where('item_id', $id)->where('type', LogEntity::TYPE_EXPENDITURE)->sum('money');
        ItemEntity::auth()->where('id', $id)->update([
            'price' => $price
        ]);
    }

    public static function remove(int $id) {
        $model = ItemEntity::auth()->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('不存在');
        }
        $model->delete();
        LogEntity::auth()->where('item_id', $id)->where('type', LogEntity::TYPE_EXPENDITURE)->update([
            'item_id' => 0
        ]);
    }
}