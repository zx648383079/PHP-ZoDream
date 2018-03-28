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
        return 'money_account';
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
            'name' => 'Name',
            'money' => 'Money',
            'status' => 'Status',
            'frozen_money' => 'Frozen Money',
            'remark' => 'Remark',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getTotalAttribute() {
        return $this->money + $this->frozen_money;
    }
}