<?php
namespace Module\Wallet\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $payment_id
 * @property string $payment_name
 * @property integer $type
 * @property integer $user_id
 * @property string $data
 * @property integer $status
 * @property float $amount
 * @property string $currency
 * @property float $currency_money
 * @property string $trade_no
 * @property integer $begin_at
 * @property integer $confirm_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class PayLogEntity extends Entity {
    public static function tableName(): string {
        return 'trade_pay_log';
    }

    protected function rules(): array {
		return [
            'payment_id' => 'required|int',
            'payment_name' => 'string:0,30',
            'type' => 'int:0,127',
            'user_id' => 'required|int',
            'data' => 'string:0,255',
            'status' => 'int:0,127',
            'amount' => 'numeric',
            'currency' => 'string:0,10',
            'currency_money' => 'numeric',
            'trade_no' => 'string:0,100',
            'begin_at' => 'int',
            'confirm_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'payment_id' => 'Payment Id',
            'payment_name' => 'Payment Name',
            'type' => 'Type',
            'user_id' => 'User Id',
            'data' => 'Data',
            'status' => 'Status',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'currency_money' => 'Currency Money',
            'trade_no' => 'Trade No',
            'begin_at' => 'Begin At',
            'confirm_at' => 'Confirm At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}