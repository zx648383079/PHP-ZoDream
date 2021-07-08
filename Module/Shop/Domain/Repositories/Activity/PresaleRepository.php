<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Activity;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\PresaleLogModel;
use Module\Shop\Domain\Repositories\GoodsRepository;

class PresaleRepository {

    public static function getList(string $keywords = '') {
        $time = time();
        $query = ActivityModel::with('goods');
        $page = $query->where('type', ActivityModel::TYPE_PRE_SALE)
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

    public static function get(int $id, bool $full = false) {
        $model = ActivityModel::where('type', ActivityModel::TYPE_PRE_SALE)
            ->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('活动不存在');
        }
        $data = static::formatItem($model);
        if ($full) {
            $data['goods'] = GoodsRepository::detail(intval($model->scope), false);
        }
        return $data;
    }

    public static function formatItem(ActivityModel $item) {
        $data = $item->toArray();
        $data['log_count'] = PresaleLogModel::where('act_id', $item->id)->count();
        $data['price'] = $data['configure']['price_type'] < 1 ? floatval($data['configure']['price'])
            : ActivityRepository::stepPrice($data['log_count'], $data['configure']['step'], 0);
        return $data;
    }

    public static function buy(int $id, int $amount)
    {

    }

}