<?php
namespace Module\Game\CheckIn\Domain\Model;


use Domain\Model\Model;
use Module\Auth\Domain\FundAccount;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\SEO\Domain\Option;
use Zodream\Helpers\Json;

/**
 * Class CheckInModel
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $running
 * @property integer $money
 * @property string $ip
 * @property integer $method
 * @property integer $created_at
 */
class CheckInModel extends Model {

    const METHOD_WEB = 0;
    const METHOD_PC = 1;
    const METHOD_APP = 2;
    const METHOD_WX = 3;

    public bool $timestamps = false;

    public static function tableName(): string {
        return 'check_in';
    }

    protected function rules(): array {
        return [
            'user_id' => 'required|int',
            'type' => 'int:0,9',
            'running' => 'int',
            'money' => 'int',
            'ip' => 'string:0,120',
            'method' => 'int:0,99',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'type' => 'Type',
            'running' => 'Running',
            'money' => 'Money',
            'ip' => 'Ip',
            'method' => 'Method',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function scopeMonth($query, $time) {
        return $this->scopeTime($query, date('Y-m-01 00:00:00', $time), date('Y-m-31 23:59:59', $time));
    }

    public function scopeToday($query) {
        return $this->scopeTime($query, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'));
    }

    public function scopeYesterday($query) {
        $time = strtotime('-1 day');
        return $this->scopeTime($query, date('Y-m-d 00:00:00', $time), date('Y-m-d 23:59:59', $time));
    }

    public function scopeTime($query, $start_at, $end_at) {
        if (!is_numeric($start_at)) {
            $start_at = strtotime($start_at);
            $end_at = strtotime($end_at);
        }
        return $query->where('created_at', '>=', $start_at)->where('created_at', '<=', $end_at);
    }


    /**
     * 是否能签到
     * @param $user_id
     * @return bool
     */
    public static function canCheckIn($user_id) {
        if ($user_id < 1) {
            return false;
        }
        $count = static::today()->where('user_id', $user_id)
            ->count();
        return $count < 1;
    }

    /**
     * 签到
     * @param $user_id
     * @param int $method
     * @return bool|static
     * @throws \Exception
     */
    public static function checkIn($user_id, $method = 0) {
        $last = static::where('user_id', $user_id)->orderBy('created_at', 'desc')->one();
        $today = strtotime(date('Y-m-d 00:00:00'));
        if ($last && $last->getAttributeSource('created_at') > $today) {
            return false;
        }
        $running = 1;
        if (!empty($last) &&
            $last->getAttributeSource('created_at') > $today - 86400) {
            $running = $last->running + 1;
        }
        $model = static::create([
            'user_id' => $user_id,
            'type' => 0,
            'method' => $method,
            'ip' => request()->ip(),
            'running' => $running,
            'money' => static::getCheckInMoney($running),
            'created_at' => time()
        ]);
        if ($model && $model->money > 0) {
            FundAccount::change($user_id,
                FundAccount::TYPE_CHECK_IN, 0,
                $model->money, sprintf('连续签到%s天', $running), 1);
        }
        return $model;
    }

    public static function getCheckInMoney($day) {
        $data = Option::value('checkin');
        if (empty($data)) {
            return 0;
        }
        $data = Json::decode($data);
        $money = $data['basic'];
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

    /**
     * 补签
     * @param $user_id
     * @param $date Y-m-d
     * @param int $method
     */
    public static function reCheckIn($user_id, $date, $method = 0) {
        $start_at = strtotime(date($date.' 00:00:00'));
        $end_at = strtotime(date($date.' 23:59:59'));
        $count = static::time($start_at, $end_at)->where('user_id', $user_id)
            ->count();
        if ($count > 0) {
            return false;
        }
        $last = static::where('user_id', $user_id)
            ->where('created_at', '<', $start_at)
            ->orderBy('created_at', 'desc')->one();
        $running = 1;
        if (!empty($last)) {
            $running = $last->running + 1;
        }
        $model = static::create([
            'user_id' => $user_id,
            'type' => 1,
            'method' => $method,
            'ip' => app('request')->ip(),
            'running' => $running,
            'created_at' => $end_at
        ]);
        if (empty($model)) {
            return false;
        }
        // 处理连续问题
        $first = static::where('user_id', $user_id)
            ->where('created_at', '>', $end_at)
            ->orderBy('created_at', 'asc')->one();
        if (empty($first) || $first->getAttributeSource('created_at') - $end_at > 86400) {
            return false;
        }
        $last = static::where('user_id', $user_id)
            ->where('id', '>', $first->id)
            ->where('running', 1)
            ->orderBy('created_at', 'asc')->one();
        static::query()->where('user_id', $user_id)
            ->where('created_at', '>=', $first->getAttributeSource('created_at'))
            ->where('created_at', '<', $last->getAttributeSource('created_at'))
            ->updateIncrement('running', $running);
        return true;
    }

    public static function countByUser($user_id) {
        return static::where('user_id', $user_id)->count();
    }

}