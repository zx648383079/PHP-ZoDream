<?php
namespace Module\Auth\Domain\Model\Game;


use Domain\Model\Model;

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

    public $timestamps = false;

    public static function tableName() {
        return 'check_in';
    }

    protected function rules() {
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

    protected function labels() {
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


    /**
     * 是否能签到
     * @param $user_id
     * @return bool
     */
    public static function canCheckIn($user_id) {
        if ($user_id < 1) {
            return false;
        }
        $count = static::where('user_id', $user_id)
            ->where('created_at', '>=', strtotime(date('Y-m-d 00:00:00')))
            ->where('created_at', '<=', strtotime(date('Y-m-d 23:59:59')))
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
        if ($last && $last->getAttributeValue('created_at') > strtotime(date('Y-m-d 00:00:00'))) {
            return false;
        }
        $running = 1;
        if (!empty($last)) {
            $running = $last->running + 1;
        }
        return static::create([
           'user_id' => $user_id,
           'type' => 0,
           'method' => $method,
           'ip' => app('request')->ip(),
           'running' => $running,
            'created_at' => time()
        ]);
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
        $count = static::where('user_id', $user_id)
            ->where('created_at', '>=', $start_at)
            ->where('created_at', '<=', $end_at)
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
        if (empty($first) || $first->getAttributeValue('created_at') - $end_at > 86400) {
            return $model;
        }
        $last = static::where('user_id', $user_id)
            ->where('id', '>', $first->id)
            ->where('running', 1)
            ->orderBy('created_at', 'asc')->one();
        static::query()->where('user_id', $user_id)
            ->where('created_at', '>=', $first->getAttributeValue('created_at'))
            ->where('created_at', '<', $last->getAttributeValue('created_at'))
            ->updateOne('running', $running);
    }

    public static function countByUser($user_id) {
        return static::where('user_id', $user_id)->count();
    }


}