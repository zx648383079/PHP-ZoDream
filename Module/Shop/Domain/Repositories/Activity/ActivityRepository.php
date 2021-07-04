<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Activity;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\ActivityTimeModel;
use Module\Shop\Domain\Models\Activity\AuctionLogModel;
use Module\Shop\Domain\Models\Activity\SeckillGoodsModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Repositories\CategoryRepository;
use Zodream\Html\Page;

class ActivityRepository {

    public static function getList(int $type, string $keywords = '') {
        $time = time();
        $query = ActivityModel::query();
        /** @var Page $page */
        $page = $query->where('type', $type)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })
            ->where('status', 0)
            ->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)->page();
        return $page;
    }

    /**
     * 获取当前正在做的活动id
     * @param int $type
     * @return mixed
     */
    public static function getActivityId(int $type) {
        $time = time();
        return ActivityModel::where('type', $type)
            ->where('status', 0)
            ->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)->pluck('id');
    }

    public static function get(int $type, int $id) {
        $time = time();
        $model = ActivityModel::where('type', $type)
            ->where('id', $id)
            ->where('status', 0)
            ->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)->first();
        if (empty($model)) {
            throw new \Exception('活动已结束或不存在');
        }
        return $model;
    }

    public static function timeList(int $length = 5, string $start_at = '') {
        $model_list = ActivityTimeModel::orderBy('start_at', 'asc')->get();
        if (empty($model_list)) {
            return [];
        }
        $data = [];
        $is_start = false;
        $now = empty($start_at) ? strtotime(date('H:i')) : strtotime($start_at);
        $next_time = [];
        $next_day = date('Y-m-d ', $now + 86400);
        $today = date('Y-m-d ', $now);
        foreach ($model_list as $item) {
            $item = $item->toArray();
            $item['title'] = $item['start_at'];
            $next_time[] = [
                'title' => '明日'.$item['start_at'],
                'start_at' => $next_day.$item['start_at'],
                'end_at' => $next_day.$item['end_at'],
                'status' => 2
            ];
            if (!$is_start) {
                $is_start = strtotime($today.$item['end_at']) > $now;
                if (!$is_start) {
                    continue;
                }
            }
            $data[] = [
                'title' => $item['start_at'],
                'start_at' => $today.$item['start_at'],
                'end_at' => $today.$item['end_at'],
                'status' => 1
            ];
        }
        $data = array_merge($data, $next_time);
        return array_splice($data, 0, $length);
    }

    /**
     * 获取当前商品正在参加的活动
     * @param GoodsModel $goods
     * @return array
     */
    public static function goodsJoin(GoodsModel $goods) {
        $time = time();
        $items = ActivityModel::whereIn('type', [ActivityModel::TYPE_CASH_BACK, ActivityModel::TYPE_DISCOUNT])
            ->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)->get();
        if (empty($items)) {
            return [];
        }
        return array_filter($items, function ($item) use ($goods) {
            return ActivityRepository::canUseGoods($item, $goods);
        });
    }

    public static function canUseGoods(ActivityModel $item, GoodsModel $goods) {
        if (!in_array($item->type, [ActivityModel::TYPE_CASH_BACK, ActivityModel::TYPE_DISCOUNT])) {
            return false;
        }
        if ($item->scope_type == ActivityModel::SCOPE_ALL) {
            return true;
        }
        $range = explode(',', $item->scope);
        if ($item->scope_type == ActivityModel::SCOPE_GOODS) {
            return in_array($goods->id, $range);
        }
        if ($item->scope_type == ActivityModel::SCOPE_BRAND) {
            return in_array($goods->brand_id, $range);
        }
        if ($item->scope_type != ActivityModel::SCOPE_CATEGORY) {
            return true;
        }
        $args = array_intersect($range, CategoryRepository::path($goods->cat_id));
        return !empty($args);
    }
}