<?php
namespace Module\Wallet\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property integer $trade_id
 * @property string $out_request_no
 * @property string $refund_reason
 * @property float $refund_amount
 * @property string $operator_id
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class RefundEntity extends Entity {
    public static function tableName(): string {
        return 'trade_refund';
    }

    protected function rules(): array {
		return [
            'trade_id' => 'required|int',
            'out_request_no' => 'required|string:0,64',
            'refund_reason' => 'string:0,255',
            'refund_amount' => 'required|numeric',
            'operator_id' => 'string:0,28',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'trade_id' => 'Trade Id',
            'out_request_no' => 'Out Request No',
            'refund_reason' => 'Refund Reason',
            'refund_amount' => 'Refund Amount',
            'operator_id' => 'Operator Id',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}