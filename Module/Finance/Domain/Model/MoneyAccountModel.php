<?php
namespace Module\Finance\Domain\Model;


use Domain\Model\Model;

/**
 * 资金账户
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 * @property float $money
 * @property float $frozen_money
 * @property boolean $status
 * @property string $remark
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class MoneyAccountModel extends Model {

    public static function tableName() {
        return 'finance_money_account';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,35',
            'money' => 'numeric',
            'status' => 'int:0,9',
            'frozen_money' => 'numeric',
            'remark' => '',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'money' => '金额',
            'status' => '状态',
            'frozen_money' => '冻结资金',
            'remark' => '备注',
            'deleted_at' => '删除时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getTotalAttribute() {
        return $this->money + $this->frozen_money;
    }
}