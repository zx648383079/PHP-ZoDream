<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\Contact\Domain\Model\SubscribeModel;

class SubscribeRepository {

    public static function getList(string $keywords = '') {
        return SubscribeModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['email', 'name']);
        })->orderBy([
            'status' => 'asc',
            'id' => 'desc'
        ])->page();
    }

    public static function change(array|int $id, int $status): void {
        $items = ModelHelper::parseArrInt($id);
        if (empty($items)) {
            return;
        }
        SubscribeModel::whereIn('id', $items)->update([
           'status' => $status
        ]);
    }

    public static function remove(array|int $id): void {
        $items = ModelHelper::parseArrInt($id);
        if (empty($items)) {
            return;
        }
        SubscribeModel::whereIn('id', $items)->delete();
    }
}