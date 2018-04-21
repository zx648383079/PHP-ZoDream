<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

/**
 * 预算
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 * @property float $budget
 * @property float $spent
 * @property integer $cycle
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class BudgetModel extends Model {

    const CYCLE_ONCE = 0;
    const CYCLE_DAY = 1;
    const CYCLE_WEEK = 2;
    const CYCLE_MONTH = 3;
    const CYCLE_YEAR = 4;

    public static function tableName() {
        return 'budget';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,50',
            'budget' => '',
            'spent' => '',
            'cycle' => 'int:0,9',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'budget' => '预算',
            'spent' => '已花费',
            'cycle' => '周期',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 获取并更新消费
     * @return float
     */
    public function getSpent() {
        if ($this->cycle === self::CYCLE_ONCE) {
            return $this->spent;
        }
        if ($this->cycle === self::CYCLE_DAY) {
            $start_at = date('Y-m-d 00:00:00');
            $end_at = date('Y-m-d 23:59:59');
        } elseif ($this->cycle === self::CYCLE_WEEK) {
            $time = ('1' == date('w')) ? strtotime('Monday') : strtotime('last Monday');
            $start_at = date('Y-m-d 00:00:00', $time);
            $end_at = date('Y-m-d 23:59:59', strtotime('Sunday'));
        } elseif ($this->cycle == self::CYCLE_MONTH) {
            $start_at = date('Y-m-01 00:00:00');
            $end_at = date('Y-m-31 00:00:00');
        } else {
            $start_at = date('Y-01-01 00:00:00');
            $end_at = date('Y-12-31 00:00:00');
        }
        if ($this->updated_at >= strtotime($start_at) && $this->updated_at <= strtotime($end_at)) {
            return $this->spent;
        }
        $this->spent = LogModel::time($start_at, $end_at)->where('budget_id', $this->id)
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
        $this->getSpent();
        if ($this->updated_at < 1) {
            $this->updated_at = $time;
        }
    }

    public function getRemainAttribute() {
        return $this->budget - $this->getSpent();
    }

    public function getLogs() {
        if ($this->cycle === self::CYCLE_ONCE) {
            return [$this->spent];
        }
        if ($this->cycle === self::CYCLE_DAY) {
            return $this->getLogByDay();
        }
        if ($this->cycle === self::CYCLE_WEEK) {
            return $this->getLogByWeek();
        }
        if ($this->cycle === self::CYCLE_MONTH) {
            return $this->getLogByMonth();
        }
        if ($this->cycle === self::CYCLE_YEAR) {
            return $this->getLogByYear();
        }
    }

    public function getLogByDay() {
        $start_at = date('Y-m-01 00:00:00');
        $end_at = date('Y-m-31 00:00:00');
        $log_list = LogModel::time($start_at, $end_at)->where('budget_id', $this->id)->sumByDate()->pluck('money', 'day');
        return self::getLinkUpLog($log_list);
    }

    public function getLogByWeek() {
        $start_at = date('Y-01-01 00:00:00');
        $end_at = date('Y-12-31 00:00:00');
        $log_list = LogModel::time($start_at, $end_at)->where('budget_id', $this->id)->sumByDate('%Y%u', 'week')->pluck('money', 'week');
        return self::getLinkUpLog($log_list);
    }

    public function getLogByMonth() {
        $start_at = date('Y-01-01 00:00:00');
        $end_at = date('Y-12-31 00:00:00');
        $log_list = LogModel::time($start_at, $end_at)->where('budget_id', $this->id)->sumByDate('%Y%m', 'month')->pluck('money', 'month');
        return self::getLinkUpLog($log_list);
    }

    public function getLogByYear() {
        $log_list = LogModel::sumByDate('%Y', 'year')->where('budget_id', $this->id)->pluck('money', 'year');
        return self::getLinkUpLog($log_list);
    }

    public static function getLinkUpLog(array $log_list) {
        $i = min(array_keys($log_list));
        $length = max(array_keys($log_list));
        $data = [];
        for (; $i <= $length; $i++) {
            $data[$i] = isset($log_list[$i]) ? abs($log_list[$i]) : 0;
        }
        return $data;
    }
}