<?php
namespace Module\Finance\Domain\Entities;

use Domain\Entities\Entity;
/**
 * Class LogModel
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
class LogEntity extends Entity {
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
            'out_trade_no' => 'string:0-100',
            'user_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
            'happened_at' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => '类型',
            'money' => '金额',
            'frozen_money' => '冻结金额',
            'account_id' => '账户',
            'channel_id' => '渠道',
            'project_id' => '项目',
            'budget_id' => '预算',
            'remark' => '备注',
            'user_id' => 'User Id',
            'out_trade_no' => '交易单号',
            'created_at' => '记录时间',
            'updated_at' => '更新时间',
            'happened_at' => '发生时间',
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }
}