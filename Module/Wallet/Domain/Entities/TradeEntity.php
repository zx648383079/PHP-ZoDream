<?php
namespace Module\Wallet\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $open_id
 * @property string $buyer_id
 * @property string $seller_id
 * @property string $out_trade_no
 * @property string $subject
 * @property string $body
 * @property float $total_amount
 * @property string $operator_id
 * @property integer $time_expire
 * @property string $notify_url
 * @property string $return_url
 * @property string $passback_params
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class TradeEntity extends Entity {
    public static function tableName(): string {
        return 'trade';
    }

    protected function rules(): array {
		return [
            'open_id' => 'required|int',
            'buyer_id' => 'required|string:0,32',
            'seller_id' => 'required|string:0,32',
            'out_trade_no' => 'required|string:0,64',
            'subject' => 'required|string:0,255',
            'body' => 'string:0,255',
            'total_amount' => 'required|numeric',
            'operator_id' => 'string:0,28',
            'time_expire' => 'int',
            'notify_url' => 'string:0,255',
            'return_url' => 'string:0,255',
            'passback_params' => 'string:0,512',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'open_id' => 'Open Id',
            'buyer_id' => 'Buyer Id',
            'seller_id' => 'Seller Id',
            'out_trade_no' => 'Out Trade No',
            'subject' => 'Subject',
            'body' => 'Body',
            'total_amount' => 'Total Amount',
            'operator_id' => 'Operator Id',
            'time_expire' => 'Time Expire',
            'notify_url' => 'Notify Url',
            'return_url' => 'Return Url',
            'passback_params' => 'Passback Params',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}