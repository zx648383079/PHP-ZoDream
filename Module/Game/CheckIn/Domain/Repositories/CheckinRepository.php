<?php
declare(strict_types=1);
namespace Module\Game\CheckIn\Domain\Repositories;


use Module\Auth\Domain\Repositories\UserRepository;
use Module\Game\CheckIn\Domain\Model\CheckInModel;
use Module\OpenPlatform\Domain\Platform;
use Module\SEO\Domain\Model\OptionModel;
use Zodream\Helpers\Json;

class CheckinRepository {

    /**
     * @return null|CheckInModel
     * @throws \Exception
     */
    public static function today() {
        if (auth()->guest()) {
            return null;
        }
        return CheckInModel::today()->where('user_id', auth()->id())->first();
    }

    public static function todayIsChecked(int $userId): bool {
        if ($userId < 1) {
            return false;
        }
        return CheckInModel::today()->where('user_id', $userId)->count() > 0;
    }

    public static function check($method = CheckInModel::METHOD_WEB) {
        if (auth()->guest()) {
            throw new \Exception('请先登录', 401);
        }
        $model = CheckInModel::checkIn(auth()->id(), $method);
        if (empty($model)) {
            throw new \Exception('签到失败');
        }
        return $model;
    }

    public static function canCheckIn(): bool {
        if (auth()->guest()) {
            return false;
        }
        return CheckInModel::canCheckIn(auth()->id());
    }

    public static function monthLog(string $month = '') {
        $timestamp = empty($month) ? time() : strtotime($month);
        return CheckInModel::month($timestamp)->where('user_id', auth()->id())->get();
    }

    public static function formatMethod(): int {
        /** @var Platform $platform */
        $platform = app('platform');
        if (empty($platform)) {
            return CheckInModel::METHOD_WEB;
        }
        return intval($platform->type());
    }

    public static function statistics() {
        $today_count = CheckInModel::today()->count();
        $yesterday_count = CheckInModel::yesterday()->count();
        $max_day = CheckInModel::today()->max('running');
        $avg_day = round(CheckInModel::today()->avg('running'), 2);
        $day_list = CheckInModel::today()->groupBy('running')->asArray()->get('COUNT(*) AS count,running as day');
        return compact('today_count', 'yesterday_count', 'max_day', 'avg_day', 'day_list');
    }

    public static function option() {
        return OptionModel::findCodeJson('checkin', [
            'basic' => 1,
            'loop' => 0,
            'plus' => []
        ]);
    }

    public static function optionSave(int $basic = 1, int $loop = 0, array $plus = []) {
        ksort($plus);
        OptionModel::insertOrUpdate('checkin', Json::encode(
            compact('basic', 'loop', $plus)
        ), '签到');
    }

    public static function logList(string $keywords = '', string $date = '') {
        $time = empty($date) ? time() : strtotime($date);
        return CheckInModel::time(date('Y-m-d 00:00:00', $time), date('Y-m-d 23:59:59', $time))
            ->with('user')->when(!empty($keywords), function ($query) use ($keywords) {
                $userId = UserRepository::searchUserId($keywords);
                if (empty($userId)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->page();
    }
}