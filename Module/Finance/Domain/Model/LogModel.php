<?php
namespace Module\Finance\Domain\Model;

use Carbon\Carbon;
use Domain\Model\Model;
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
 * @property integer $user_id
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
        return 'finance_log';
    }

    protected function rules() {
        return [
            'type' => 'int:0,9',
            'money' => 'numeric',
            'frozen_money' => 'numeric',
            'account_id' => 'required|int',
            'channel_id' => 'int',
            'project_id' => 'int',
            'budget_id' => 'int',
            'remark' => '',
            'user_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
            'happened_at' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => '类型呢',
            'money' => '金额',
            'frozen_money' => '冻结金额',
            'account_id' => '账户',
            'channel_id' => '渠道',
            'project_id' => '项目',
            'budget_id' => '预算',
            'remark' => '备注',
            'user_id' => 'User Id',
            'created_at' => '记录时间',
            'updated_at' => '更新时间',
            'happened_at' => '发生时间',
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }

    public function getDayAttribute() {
        return date('d', strtotime($this->happened_at));
    }

    public function scopeMonth($query, $time) {
        return $this->scopeTime($query, date('Y-m-01 00:00:00', $time), date('Y-m-31 23:59:59', $time));
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
}