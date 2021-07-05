<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Activity;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Zodream\Database\Relation;

class MixRepository {

    public static function getList(string $keywords = '') {
        $time = time();
        $page = ActivityModel::query()->where('type', ActivityModel::TYPE_MIX)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })
            ->where('status', 0)
            ->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)->page();
        $page->map(function ($item) {
            return static::formatItem($item);
        });
        return $page;
    }

    private static function formatItem(ActivityModel $item) {
        $data = $item->toArray();
        if (!isset($data['configure']['goods'])) {
            return $data;
        }
        $data['goods_items']  = Relation::create($data['configure']['goods'], [
            'goods' => [
                'query' => GoodsSimpleModel::query(),
                'link' => ['goods_id', 'id'],
                'type' => Relation::TYPE_ONE
            ]
        ]);
        return $data;
    }
}