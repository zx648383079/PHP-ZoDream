<?php
namespace Module\Finance\Domain\Model;


use Module\Finance\Domain\Entities\AccountEntity;

/**
 * 资金账户
 * @package Module\Finance\Domain\Model
 * @property integer $id
 * @property string $name
 * @property float $money
 * @property float $frozen_money
 * @property float $total
 * @property boolean $status
 * @property string $remark
 * @property integer $user_id
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class MoneyAccountModel extends AccountEntity {

    public function getTotalAttribute() {
        return $this->money + $this->frozen_money;
    }
}