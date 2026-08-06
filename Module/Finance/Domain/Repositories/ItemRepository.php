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
            $item['days'] = ceil((($item['deleted_at'] > 0 ? $item['deleted_at'] : $now) - $item['created_at']) / 86400);
            $item['avg'] = round($item['price'] / $item['days'], 2);
            return $item;
        });
        $res = $items->toArray();
        if ($items->getIndex() === 1) {
            $res['subtotal'] = self::subtotal();
        }
        return $res;
    }

    public static function subtotal(): array {
        $total = ItemEntity::auth()->sum('price');
        $avg = 0;
        $now = time();
        ItemEntity::auth()->select('price', 'created_at')->asArray()->each(function(array $item) use ($now, &$avg) {
            $avg += floatval($item['price']) / ceil(($now - $item['created_at']) / 86400);
        });
        $avg = round($avg, 2);
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
        if ($model->status == 1) {
            $model->deleted_at = 0;
        }
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

    public static function unbinding(int $log) {
        $itemId = intval(LogEntity::auth()->where('id', $log)->value('item_id'));
        if (!$itemId) {
            return;
        }
        LogEntity::auth()->where('id', $log)->where('type', LogEntity::TYPE_EXPENDITURE)->update([
            'item_id' => 0
        ]);
        static::refresh($itemId);
    }

    public static function refresh(int $id) {
        $data = LogEntity::auth()->where('item_id', $id)->where('type', LogEntity::TYPE_EXPENDITURE)
            ->selectRaw('SUM(money) as total, MIN(happened_at) as time')->asArray()->first();
        $updated = [
            'price' => !empty($data) ? $data['total'] : 0,
        ];
        if (!empty($data) && !empty($data['time'])) {
            $updated['created_at'] = strtotime($data['time']);
        }
        ItemEntity::auth()->where('id', $id)->update($updated);
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