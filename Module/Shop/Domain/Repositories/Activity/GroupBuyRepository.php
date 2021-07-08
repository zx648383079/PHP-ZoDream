<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Activity;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\GroupBuyLogModel;

class GroupBuyRepository {

    public static function getList(string $keywords = '') {
        $time = time();
        $page = ActivityModel::with('goods')->where('type', ActivityModel::TYPE_GROUP_BUY)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })
            ->where('status', 0)
            ->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)->page();
        return $page;
    }

    private static function formatItem(ActivityModel $item) {
        $data = $item->toArray();
        $data['log_count'] = GroupBuyLogModel::where('act_id', $item->id)->count();
        $data['price'] = ActivityRepository::stepPrice($data['log_count'], $data['configure']['step']);
        return $data;
    }
}