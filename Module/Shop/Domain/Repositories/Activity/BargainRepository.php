<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Activity;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\BargainLogModel;
use Module\Shop\Domain\Repositories\GoodsRepository;
use Zodream\Html\Page;

class BargainRepository {

    public static function getList(string $keywords = '') {
        $time = time();
        $query = ActivityModel::query()->with('goods');
        /** @var Page $page */
        $page = $query->where('type', ActivityModel::TYPE_BARGAIN)
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
        $model = ActivityModel::where('type', ActivityModel::TYPE_BARGAIN)
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
        $source = BargainLogModel::where('act_id', $item->id)
            ->selectRaw('COUNT(*) as count,MAX(bid) as bid')->first();
        $data['log_count'] = intval($source['count']);
        $data['price'] = floatval($source['bid']);
        return $data;
    }

    public static function logList(int $activity) {
        return BargainLogModel::with('user')
            ->where('act_id', $activity)
            ->orderBy('id', 'desc')
            ->page();
    }


}