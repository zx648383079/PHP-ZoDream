<?php
namespace Module\Finance\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 资金账户
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 * @property float $money
 * @property float $frozen_money
 * @property boolean $status
 * @property string $remark
 * @property integer $user_id
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class AccountEntity extends Entity {
    public static function tableName() {
        return 'finance_money_account';
    }

    public function rules() {
        return [
            'name' => 'required|string:0,35',
            'money' => 'numeric',
            'status' => 'int:0,9',
            'frozen_money' => 'numeric',
            'remark' => '',
            'user_id' => 'required|int',
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
            'user_id' => 'User Id',
            'deleted_at' => '删除时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }
}