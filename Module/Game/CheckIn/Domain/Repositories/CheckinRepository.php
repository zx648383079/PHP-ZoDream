<?php
declare(strict_types=1);
namespace Module\Game\CheckIn\Domain\Repositories;


use Module\Auth\Domain\Repositories\UserRepository;
use Module\Game\CheckIn\Domain\Model\CheckInModel;
use Module\OpenPlatform\Domain\Platform;
use Module\SEO\Domain\Model\OptionModel;
use Module\SEO\Domain\Option;
use Module\Wallet\Domain\FundAccount;
use Zodream\Helpers\Json;

class CheckinRepository {

    const METHOD_WEB = 0;
    const METHOD_PC = 1;
    const METHOD_APP = 2;
    const METHOD_WX = 3;

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

    /**
     * 签到
     */
    public static function check(int $user, int $method = self::METHOD_WEB) {
        if ($user === 0) {
            throw new \Exception('请先登录', 401);
        }
        $last = CheckInModel::where('user_id', $user)->orderBy('created_at', 'desc')->first();
        $today = strtotime(date('Y-m-d 00:00:00'));
        if ($last && $last->getAttributeSource('created_at') > $today) {
            throw new \Exception('签到失败');
        }
        $running = 1;
        if (!empty($last) &&
            $last->getAttributeSource('created_at') > $today - 86400) {
            $running = $last->running + 1;
        }
        $model = CheckInModel::create([
            'user_id' => $user,
            'type' => 0,
            'method' => $method,
            'ip' => request()->ip(),
            'running' => $running,
            'money' => static::getCheckInMoney($running),
            'created_at' => time()
        ]);
        if ($model && $model->money > 0) {
            FundAccount::change($user,
                FundAccount::TYPE_CHECK_IN, 0,
                $model->money, sprintf('连续签到%s天', $running), 1);
        }
        return $model;
    }

     /**
     * 补签
     * @param int $user
     * @param $date Y-m-d
     * @param int $method
     */
    public static function reCheckIn($user, string $date, int $method = 0): bool {
        $start_at = strtotime(date($date.' 00:00:00'));
        $end_at = strtotime(date($date.' 23:59:59'));
        $count = CheckInModel::time($start_at, $end_at)->where('user_id', $user)
            ->count();
        if ($count > 0) {
            return false;
        }
        $last = CheckInModel::where('user_id', $user)
            ->where('created_at', '<', $start_at)
            ->orderBy('created_at', 'desc')->one();
        $running = 1;
        if (!empty($last)) {
            $running = $last->running + 1;
        }
        $model = CheckInModel::create([
            'user_id' => $user,
            'type' => 1,
            'method' => $method,
            'ip' => request()->ip(),
            'running' => $running,
            'created_at' => $end_at
        ]);
        if (empty($model)) {
            return false;
        }
        // 处理连续问题
        $first = CheckInModel::where('user_id', $user)
            ->where('created_at', '>', $end_at)
            ->orderBy('created_at', 'asc')->one();
        if (empty($first) || $first->getAttributeSource('created_at') - $end_at > 86400) {
            return false;
        }
        $last = CheckInModel::where('user_id', $user)
            ->where('id', '>', $first->id)
            ->where('running', 1)
            ->orderBy('created_at', 'asc')->one();
        CheckInModel::query()->where('user_id', $user)
            ->where('created_at', '>=', $first->getAttributeSource('created_at'))
            ->where('created_at', '<', $last->getAttributeSource('created_at'))
            ->updateIncrement('running', $running);
        return true;
    }

    public static function canCheckIn(): bool {
        if (auth()->guest()) {
            return false;
        }
        $user = auth()->id();
        if ($user < 1) {
            return false;
        }
        $count = static::today()->where('user_id', $user)
            ->count();
        return $count < 1;
    }

    private static function getCheckInMoney(int $day): int {
        $data = Option::value('checkin');
        if (empty($data)) {
            return 0;
        }
        $data = Json::decode($data);
        $money = intval($data['basic']);
        if ($data['loop'] > 0) {
            $day %= $data['loop'];
            if ($day === 0) {
                $day = $data['loop'];
            }
        }
        if (isset($data['plus'][$day])) {
            $money += $data['plus'][$day];
        }
        return $money;
    }

    public static function monthLog(string $month = '') {
        $timestamp = empty($month) ? time() : strtotime($month);
        return CheckInModel::month($timestamp)->where('user_id', auth()->id())->get();
    }

    public static function countByUser(int $user): int {
        return CheckInModel::where('user_id', $user)->count();
    }

    public static function formatMethod(): int {
        /** @var Platform $platform */
        $platform = app('platform');
        if (empty($platform)) {
            return self::METHOD_WEB;
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
            compact('basic', 'loop', 'plus')
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