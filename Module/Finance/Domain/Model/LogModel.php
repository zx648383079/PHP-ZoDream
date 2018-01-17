<?php
namespace Module\Finance\Domain\Model;

use Domain\Model\Model;

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
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $happened_at
 */
class LogModel extends Model {

    /**
     * 支出
     */
    const TYPE_EXPENDITURE = 0;
    /**
     * 收入
     */
    const TYPE_INCOME = 1;

    public static function tableName() {
        return 'money_log';
    }

    protected function rules() {
        return [
            'type' => 'int:0-1',
            'money' => '',
            'frozen_money' => '',
            'account_id' => 'required|int',
            'channel_id' => 'int',
            'project_id' => 'int',
            'remark' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
            'happened_at' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => 'Type',
            'money' => 'Money',
            'frozen_money' => 'Frozen Money',
            'account_id' => 'Account Id',
            'channel_id' => 'Channel Id',
            'project_id' => 'Project Id',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'happened_at' => 'Happened At',
        ];
    }

    public function getDayAttribute() {
        return date('d', strtotime($this->happened_at));
    }

    public function getHappenedAtAttribute() {
        return $this->formatTimeAttribute('happened_at');
    }

    public function scopeMonth($query, $time) {
        return $this->scopeTime($query, date('Y-m-01 00:00:00', $time), date('Y-m-31 00:00:00', $time));
    }

    public function scopeTime($query, $start_at, $end_at) {
        return $query->where('happened_at', '>=', $start_at)->where('happened_at', '<=', $end_at);
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