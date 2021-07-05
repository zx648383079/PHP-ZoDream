<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Activity;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\AuctionLogModel;
use Module\Shop\Domain\Repositories\GoodsRepository;
use Zodream\Html\Page;

class AuctionRepository {

    public static function getList(string $keywords = '') {
        $time = time();
        $query = ActivityModel::query()->with('goods');
        /** @var Page $page */
        $page = $query->where('type', ActivityModel::TYPE_AUCTION)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })
            ->where('status', 0)
            ->where('start_at', '<=', $time)
            ->where('end_at', '>', $time)->page();
        $page->map(function ($item) {
            return static::formatAuction($item);
        });
        return $page;
    }


    public static function auctionDetail(int $id, bool $full = false) {
        $model = ActivityModel::where('type', ActivityModel::TYPE_AUCTION)
            ->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('活动不存在');
        }
        $data = static::formatAuction($model);
        if ($full) {
            $data['goods'] = GoodsRepository::detail(intval($model->scope), false);
        }
        return $data;
    }


    public static function formatAuction(ActivityModel $item) {
        $data = $item->toArray();
        $source = AuctionLogModel::where('act_id', $item->id)
            ->selectRaw('COUNT(*) as count,MAX(bid) as bid')->first();
        $data['log_count'] = intval($source['count']);
        $data['price'] = floatval($source['bid']);
        return $data;
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