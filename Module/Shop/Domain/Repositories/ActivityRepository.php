<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\ActivityTimeModel;
use Module\Shop\Domain\Models\Activity\AuctionLogModel;
use Module\Shop\Domain\Models\Activity\SeckillGoodsModel;
use Zodream\Html\Page;

class ActivityRepository {

    public static function getList(int $type, string $keywords = '') {
        $time = time();
        $query = ActivityModel::query();
        if (in_array($type, [ActivityModel::TYPE_AUCTION, ActivityModel::TYPE_PRE_SALE, ActivityModel::TYPE_BARGAIN])) {
            $query->with('goods');
        }
        return $query->where('type', $type)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })
            ->where('status', 0)
            ->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)->page();
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


    public static function secKillGoodsList(int $act_id = 0, int $time_id = 0, string $time = '') {
        $actIds = static::getActivityId(ActivityModel::TYPE_SEC_KILL);
        if ($act_id > 0 && !in_array($act_id, $actIds)) {
            return new Page(0);
        }
        return SeckillGoodsModel::with('goods')
            ->when($act_id > 0, function ($query) use ($act_id) {
                $query->where('act_id', $act_id);
            }, function ($query) use ($actIds) {
                $query->whereIn('act_id', $actIds);
            })->when($time_id > 0, function ($query) use ($time_id) {
                $query->where('time_id', $time_id);
            })->when(!empty($time), function ($query) use ($time, $actIds) {
                $time = strtotime($time);
                $ids = ActivityModel::where('start_at', '<=', $time)
                    ->whereIn('id', $actIds)
                    ->where('end_at', '>', $time)->pluck('id');
                if (empty($ids)) {
                    return $query->isEmpty();
                }
                $time_ids = ActivityTimeModel::where('start_at', date('H:i', $time))->pluck('id');
                if (empty($time_ids)) {
                    return $query->isEmpty();
                }
                $query->whereIn('act_id', $ids)->whereIn('time_id', $time_ids);
            })->page();
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
                'title' => $item['start_at'],
                'start_at' => $next_day.$item['start_at'],
                'end_at' => $next_day.$item['end_at'],
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
            ];
        }
        $data = array_merge($data, $next_time);
        return array_splice($data, 0, $length);
    }

    public static function auctionLogList(int $activity) {
        return AuctionLogModel::with('user')
            ->where('act_id', $activity)
            ->orderBy('id', 'desc')
            ->page();
    }

    public static function auctionBid(int $activity, float $money = 0) {
        $log = new AuctionLogModel([
            'act_id' => $activity,
            'bid' => $money,
            'user_id' => auth()->id()
        ]);
        $instance = $log->auction();
        if (!$instance->auction()) {
            return false;
        }
        return $log;
    }
}