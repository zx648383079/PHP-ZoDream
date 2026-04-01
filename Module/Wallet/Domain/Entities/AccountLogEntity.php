<?php
namespace Module\Wallet\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $item_id
 * @property integer $money
 * @property integer $total_money
 * @property integer $status
 * @property string $remark
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $credits
 * @property integer $total_credits
 */
class AccountLogEntity extends Entity {
    public static function tableName(): string {
        return 'user_account_log';
    }

    protected function rules(): array {
		return [
            'user_id' => 'int',
            'type' => 'int:0,127',
            'item_id' => 'int',
            'money' => 'required|int',
            'total_money' => 'required|int',
            'status' => 'int:0,127',
            'remark' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
            'credits' => 'required|int',
            'total_credits' => 'required|int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'type' => 'Type',
            'item_id' => 'Item Id',
            'money' => 'Money',
            'total_money' => 'Total Money',
            'status' => 'Status',
            'remark' => 'Remark',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'credits' => 'Credits',
            'total_credits' => 'Total Credits',
        ];
	}
}