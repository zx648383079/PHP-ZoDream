<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Activity;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\BargainLogModel;
use Module\Shop\Domain\Models\Activity\BargainUserModel;
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
        $data['log_count'] = BargainUserModel::where('act_id', $item->id)->count();
        $data['join_log'] = BargainUserModel::where('act_id', $item->id)->where('user_id', auth()->id())
            ->first();
        $data['price'] = empty($data['join_log']) ? $item->goods->price : $data['join_log']['price'];
        return $data;
    }

    public static function logList(int $activity) {
        return BargainLogModel::with('user')
            ->where('act_id', $activity)
            ->orderBy('id', 'desc')
            ->page();
    }

    public static function getWithLog(int $id, string $log)
    {
        $data = static::get($id, true);
        $data['log'] = BargainUserModel::where('act_id', $id)->where('id', $log)
            ->first();
        if (!empty($data['log'])) {
            throw new \Exception('数据错误');
        }
        $data['price'] = $data['log']->price;
        return $data;
    }

    public static function cutLogList(int $activity, string $log)
    {
        return BargainLogModel::with('user')
            ->where('act_id', $activity)
            ->where('bargain_id', $log)->orderBy('id', 'desc')->page();
    }

    public static function apply(int $id)
    {
        $log = BargainUserModel::where('act_id', $id)->where('user_id', auth()->id())
            ->first();
        if (!empty($log)) {
            throw new \Exception('已参加');
        }
        $model = ActivityModel::where('type', ActivityModel::TYPE_BARGAIN)
            ->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('活动不存在');
        }
        return BargainUserModel::createOrThrow([
            'act_id' => $id,
            'user_id' => auth()->id(),
            'goods_id' => $model->scope,
            'price' => $model->goods->price,
        ]);
    }

    public static function cut(int $id, string $logId)
    {
        if (static::isCut($id, $logId)) {
            throw new \Exception('每人只能砍一次');
        }
        // 每人每天只能帮砍3次
        $count = BargainLogModel::where('user_id', auth()->id())
            ->where('created_at', '>=', strtotime(date('Y-m-d 00:00:00')))
            ->count();
        if ($count >= 3) {
            throw new \Exception('每人每天只能帮砍3次');
        }
        $model = ActivityModel::where('type', ActivityModel::TYPE_BARGAIN)
            ->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('活动不存在');
        }
        $log = BargainUserModel::findOrThrow($logId, '数据错误');
        $amount = static::bargainPrice($model, $log->price);
        if ($amount <= 0) {
            throw new \Exception('无法砍价');
        }
        $log->price -= $amount;
        BargainLogModel::createOrThrow([
            'bargain_id' => $log->id,
            'user_id' => auth()->id(),
            'amount' => $amount,
            'price' => $log->price,
        ]);
        $log->save();
        return $log;
    }

    private static function bargainPrice(ActivityModel $model, float $current): float {
        if ($current <= 0) {
            return 0;
        }
        $amount = floatval($model->configure['min']);
        if ($amount <= 0) {
            $amount = .01;
        }
        if ($current - $amount < 0) {
            $amount = $current;
        }
        return $amount;
    }

    private static function isCut(int $id, string $log, int $userId = -1): bool {
        if ($userId < 0) {
            $userId = auth()->id();
        }
        return BargainLogModel::where('act_id', $id)->where('user_id', $userId)
            ->where('bargain_id', $log)
            ->count();
    }

}