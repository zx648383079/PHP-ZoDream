<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Activity;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\ActivityTimeModel;
use Module\Shop\Domain\Models\Activity\SeckillGoodsModel;
use Zodream\Html\Page;

class SeckillRepository {

    public static function getList(string $keywords = '') {
        $time = time();
        $query = ActivityModel::with('goods');
        /** @var Page $page */
        return $query->where('type', ActivityModel::TYPE_SEC_KILL)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })
            ->where('status', 0)
            ->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)->page();
    }


    public static function get(int $id) {
        $time = time();
        $model = ActivityModel::where('type', ActivityModel::TYPE_SEC_KILL)
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
        $actIds = ActivityRepository::getActivityId(ActivityModel::TYPE_SEC_KILL);
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

}