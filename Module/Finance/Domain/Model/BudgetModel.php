<?php
namespace Module\Finance\Domain\Model;

use Module\Finance\Domain\Entities\BudgetEntity;
use Zodream\Helpers\Time;

/**
 * 预算
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 * @property float $budget
 * @property float $spent
 * @property integer $cycle
 * @property integer $user_id
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class BudgetModel extends BudgetEntity {

    /**
     * 获取并更新消费
     * @return float
     */
    public function getSpent() {
        if ($this->cycle == self::CYCLE_ONCE) {
            return $this->spent;
        }
        if ($this->cycle == self::CYCLE_DAY) {
            $start_at = date('Y-m-d 00:00:00');
            $end_at = date('Y-m-d 23:59:59');
        } elseif ($this->cycle == self::CYCLE_WEEK) {
            $time = ('1' == date('w')) ? strtotime('Monday') : strtotime('last Monday');
            $start_at = date('Y-m-d 00:00:00', $time);
            $end_at = date('Y-m-d 23:59:59', strtotime('Sunday'));
        } elseif ($this->cycle == self::CYCLE_MONTH) {
            list($start_at, $end_at) = Time::month(time(), 'Y-m-d H:i:s');
        } else {
            $start_at = date('Y-01-01 00:00:00');
            $end_at = date('Y-m-t 00:00:00', strtotime(date('Y-12-01 00:00:00')));
        }
        if ($this->updated_at >= strtotime($start_at) && $this->updated_at <= strtotime($end_at)) {
            return $this->spent;
        }
        $this->spent = LogModel::time($start_at, $end_at)->where('user_id', $this->user_id)->where('budget_id', $this->id)
            ->where('type', LogModel::TYPE_EXPENDITURE)->sum('money');
        $this->save();
        return $this->spent;
    }

    /**
     * 刷新消费
     */
    public function refreshSpent() {
        if (empty($this->id)) {
            return;
        }
        $time = $this->updated_at;
        $this->updated_at = 0;
        if ($this->cycle == self::CYCLE_ONCE) {
            $this->spent = LogModel::where('user_id', $this->user_id)->where('budget_id', $this->id)
                ->where('type', LogModel::TYPE_EXPENDITURE)->sum('money');
            $this->save();
        }
        $this->getSpent();
        if ($this->updated_at < 1) {
            $this->updated_at = $time;
        }
    }

    public function getRemainAttribute() {
        return $this->budget - $this->getSpent();
    }

    public function getLogs() {
        if ($this->cycle == self::CYCLE_ONCE) {
            return [$this->spent];
        }
        if ($this->cycle == self::CYCLE_DAY) {
            return $this->getLogByDay();
        }
        if ($this->cycle == self::CYCLE_WEEK) {
            return $this->getLogByWeek();
        }
        if ($this->cycle == self::CYCLE_MONTH) {
            return $this->getLogByMonth();
        }
        if ($this->cycle == self::CYCLE_YEAR) {
            return $this->getLogByYear();
        }
        return [];
    }

    public function getLogByAll() {
        return LogModel::where('user_id', $this->user_id)->where('budget_id', $this->id)->pluck('money', 'happened_at');
    }

    public function getLogByDay() {
        $start_at = date('Y-m-01 00:00:00');
        $end_at = date('Y-m-31 00:00:00');
        $log_list = LogModel::time($start_at, $end_at)->where('user_id', $this->user_id)->where('budget_id', $this->id)->sumByDate()->pluck('money', 'day');
        return self::getLinkUpLog($log_list, date('Ymd'), function ($i) {
            $y = floor($i / 10000);
            $m = floor($i % 10000 / 100);
            $d = $i % 100;
            if ($d < 28) {
                return $i + 1;
            }
            if (date('t', strtotime(sprintf('%s-%s-1', $y, $m))) > $d) {
                return $i + 1;
            }
            return $m > 11 ? ($y + 1) * 10000 + 101 : ($y * 10000 + ($m + 1) * 100 + 1);
        });
    }

    public function getLogByWeek() {
        $month = date('m');
        $start_at = sprintf('%s-01-01 00:00:00', date('Y') - ($month > 5 ? 0  : 1));
        $end_at = date('Y-12-31 00:00:00');
        $log_list = LogModel::time($start_at, $end_at)->where('user_id', $this->user_id)->where('budget_id', $this->id)->sumByDate('%Y%u', 'week')->pluck('money', 'week');
        return self::getLinkUpLog($log_list, date('YW'), function ($i) {
            return ($i % 100 >= 53 ? ceil($i / 100) * 100 : $i) + 1;
        });
    }

    public function getLogByMonth() {
        $month = date('m');
        $start_at = sprintf('%s-01-01 00:00:00', date('Y') - ($month > 5 ? 1  : 2));
        $end_at = date('Y-12-31 00:00:00');
        $log_list = LogModel::time($start_at, $end_at)->where('user_id', $this->user_id)->where('budget_id', $this->id)->sumByDate('%Y%m', 'month')->pluck('money', 'month');
        return self::getLinkUpLog($log_list, date('Ym'), function ($i) {
            return ($i % 100 >= 12 ? ceil($i / 100) * 100 : $i) + 1;
        });
    }

    public function getLogByYear() {
        $log_list = LogModel::sumByDate('%Y', 'year')->where('user_id', $this->user_id)->where('budget_id', $this->id)->pluck('money', 'year');
        return self::getLinkUpLog($log_list, date('Y'));
    }

    public static function getLinkUpLog($log_list, $max, callable $next = null) {
        if (empty($log_list)) {
            return [];
        }
        if (empty($next)) {
            $next = function ($i) {
                return $i + 1;
            };
        }
        $i = min(array_keys($log_list));
        $length = max(array_keys($log_list));
        if ($i < $max && $length < $max) {
            $length = $max;
        }
        $data = [];
        for (; $i <= $length;) {
            $data[$i] = isset($log_list[$i]) ? abs($log_list[$i]) : 0;
            $i = $next($i);
        }
        return $data;
    }
}