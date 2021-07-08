<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Activity;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\TrialLogModel;
use Module\Shop\Domain\Repositories\GoodsRepository;

class TrialRepository {

    public static function getList(string $keywords = '') {
        $time = time();
        $page = ActivityModel::with('goods')->where('type', ActivityModel::TYPE_FREE_TRIAL)
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
        $data['log_count'] = TrialLogModel::where('act_id', $item->id)->count();
        $data['is_joined'] = static::isJoined($item->id);
        return $data;
    }

    private static function isJoined(int $id, int $userId = -1): bool {
        if ($userId < 0) {
            $userId = auth()->id();
        }
        return TrialLogModel::where('act_id', $id)->where('user_id', $userId)
            ->count() > 0;
    }

    public static function get(int $id, bool $full)
    {
        $model = ActivityModel::where('type', ActivityModel::TYPE_FREE_TRIAL)
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

    public static function logList(int $id)
    {
        return TrialLogModel::with('user')
            ->where('act_id', $id)->orderBy('id', 'desc')->page();
    }

    public static function apply(int $id)
    {
        $model = ActivityModel::where('type', ActivityModel::TYPE_FREE_TRIAL)
            ->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('活动不存在');
        }
        if (static::isJoined($id)) {
            throw new \Exception('已经申请过了');
        }
        return TrialLogModel::createOrThrow([
            'act_id' => $id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * 抽取中奖用户
     * @param int $id
     */
    public static function extract(int $id) {

    }

    public static function saveReport(int $id, $data)
    {

    }
}