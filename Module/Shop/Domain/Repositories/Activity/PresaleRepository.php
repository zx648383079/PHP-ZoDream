<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Activity;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;

class PresaleRepository {

    public static function getList(string $keywords = '') {
        $time = time();
        $query = ActivityModel::with('goods');
        return $query->where('type', ActivityModel::TYPE_PRE_SALE)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })
            ->where('status', 0)
            ->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)->page();
    }

}