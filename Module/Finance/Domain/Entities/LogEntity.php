<?php
namespace Module\Finance\Domain\Entities;

use Domain\Entities\Entity;
/**
 * Class LogModel
 * @property integer $id
 * @property integer $parent_id
 * @property integer $type
 * @property float $money
 * @property float $frozen_money
 * @property integer $account_id
 * @property integer $channel_id
 * @property integer $project_id
 * @property integer $budget_id
 * @property string $remark
 * @property string $happened_at
 * @property string $out_trade_no
 * @property integer $user_id
 * @property string $trading_object
 * @property integer $created_at
 * @property integer $updated_at
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

    const TYPE_LEND = 2; // 借出
    const TYPE_BORROW = 3; // 借入

    public static function tableName(): string {
        return 'finance_log';
    }

    public function rules(): array {
        return [
            'parent_id' => 'int',
            'type' => 'int:0,127',
            'money' => '',
            'frozen_money' => '',
            'account_id' => 'required|int',
            'channel_id' => 'int',
            'project_id' => 'int',
            'budget_id' => 'int',
            'remark' => '',
            'happened_at' => 'required',
            'out_trade_no' => 'string:0,100',
            'user_id' => 'required|int',
            'trading_object' => 'string:0,100',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'parent_id' => 'Parent Id',
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
            'trading_object' => '交易对象',
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }
}