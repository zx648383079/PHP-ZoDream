<?php
namespace Module\Finance\Domain\Model;

use Module\Finance\Domain\Entities\LogEntity;
use Zodream\Database\Model\Query;

/**
 * Class LogModel
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property integer $type
 * @property float $money
 * @property float $frozen_money
 * @property integer $account_id
 * @property integer $channel_id
 * @property integer $project_id
 * @property integer $budget_id
 * @property string $remark
 * @property string $out_trade_no
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $happened_at
 */
class LogModel extends LogEntity {


    public function getDayAttribute() {
        return date('d', strtotime($this->happened_at));
    }

    public function scopeMonth($query, $time) {
        return $this->scopeTime($query, date('Y-m-01 00:00:00', $time),
            date('Y-m-t 23:59:59', $time));
    }

    public function scopeWeek($query, $now) {
        $time = ('1' == date('w', $now)) ? strtotime('Monday', $now) : strtotime('last Monday', $now);
        return $this->scopeTime($query, date('Y-m-d 00:00:00', $time), date('Y-m-d 23:59:59', strtotime('Sunday', $now)));
    }

    public function scopeTime($query, $start_at, $end_at) {
        return $query->where('happened_at', '>=', $start_at)->where('happened_at', '<=', $end_at);
    }

    public function scopeSumByDate(Query $query, $format = '%Y%m%d', $as = 'day', $fields = 'SUM(money) as money') {
        return $query->selectRaw(sprintf('DATE_FORMAT(happened_at, \'%s\') as %s, %s', $format, $as, $fields))->groupBy($as);
    }

    /**
     * 获取一个月每天的记录
     * @param array $log_list
     * @param $day_length
     * @return array
     */
    public static function getMonthLogs(array $log_list, $day_length) {
        $days = [];
        foreach ($log_list as $item) {
            $day = intval($item->day);
            if (!isset($days[$day])) {
                $days[$day] = $item->money;
                continue;
            }
            $days[$day] += $item->money;
        }
        $data = [];
        for ($i = 1; $i <= $day_length; $i++) {
            $data[$i] = isset($days[$i]) ? $days[$i] : 0;
        }
        return $data;
    }

    public static function createIfNot($data) {
        $count = static::query()->where('user_id', $data['user_id'])
            ->where('out_trade_no', $data['out_trade_no'])
            ->count();
        if ($count > 0) {
            return false;
        }
        return static::create($data);
    }
}