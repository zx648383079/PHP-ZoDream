<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Activity;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\LotteryLogModel;

class LotteryRepository {

    public static function get(int $id, bool $true)
    {
        return ActivityModel::where('type', ActivityModel::TYPE_LOTTERY)
            ->where('id', $id)->first();
    }

    public static function play(int $id, int $amount)
    {
        return [];
    }

    public static function logList(int $id)
    {
        return LotteryLogModel::with('user')
            ->where('act_id', $id)->orderBy('id', 'desc')->page();
    }
}