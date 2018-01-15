<?php
namespace Module\Finance\Domain\Model;


use Domain\Model\Model;

/**
 * 理财项目
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property float $number
 * @property float $accounted_for
 * @property float $earnings
 * @property string $start_at
 * @property string $end_at
 * @property float $earnings_number
 * @property integer $bank_id
 * @property integer $status
 * @property integer $deleted_at
 * @property integer $color
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class FinancialProjectModel extends Model {

    public static function tableName() {
        return 'financial_management';
    }


    protected function rules() {
        return [
            'id' => 'required|int',
            'name' => 'required|string:3-35',
            'alias' => 'required|string:3-50',
            'number' => 'required',
            'accounted_for' => '',
            'earnings' => '',
            'start_at' => '',
            'end_at' => '',
            'earnings_number' => '',
            'bank_id' => 'int',
            'status' => 'int:0-1',
            'deleted_at' => 'int',
            'color' => 'int:0-1',
            'remark' => '',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'ID',
            'name' => '配置项目',
            'alias' => 'Alias',
            'number' => '资金',
            'accounted_for' => 'Accounted For',
            'earnings' => '(预估)收益率',
            'start_at' => '起息日期',
            'end_at' => '到期日期',
            'earnings_number' => 'Earnings Number',
            'bank_id' => '形态',
            'status' => '状态',
            'deleted_at' => 'Deleted At',
            'color' => 'Color',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public function bank() {
        return $this->hasOne(BankModel::class, 'id', 'bank_id');
    }
}