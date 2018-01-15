<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

class LogModel extends Model {

    /**
     * 支出
     */
    const TYPE_EXPENDITURE = 0;
    /**
     * 收益
     */
    const TYPE_INCOME = 1;

    public static function tableName() {
        return 'money_log';
    }

    public function getDayAttribute() {
        return date('d', strtotime($this->happened_at));
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
            $day = $item->day;
            if (!isset($income_days[$day])) {
                $days[$day] = $item->money;
                continue;
            }
            $days[$day] += $item->money;
        }
        $data = [];
        for ($i = 1; $i <= $day_length; $i ++) {
            $data[$i] = isset($days[$i]) ? $days[$i] : 0;
        }
        return $data;
    }
}