<?php
namespace Module\Shop\Domain\Models\Activity;

use Domain\Model\Model;

/**
 * Class PresaleLogModel
 * @package Module\Shop\Domain\Models\Activity
 * @property integer $id
 * @property integer $act_id
 * @property integer $user_id
 * @property integer $order_id
 * @property integer $order_goods_id
 * @property float $order_amount
 * @property float $deposit
 * @property float $final_payment
 * @property integer $status
 * @property integer $predetermined_at
 * @property integer $final_at
 * @property integer $ship_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class PresaleLogModel extends Model {

    public static function tableName(): string {
        return 'shop_presale_log';
    }

    protected function rules(): array {
        return [
            'act_id' => 'required|int',
            'user_id' => 'required|int',
            'order_id' => 'required|int',
            'order_goods_id' => 'required|int',
            'order_amount' => 'string',
            'deposit' => 'string',
            'final_payment' => 'string',
            'status' => 'int:0,127',
            'predetermined_at' => 'int',
            'final_at' => 'int',
            'ship_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'act_id' => 'Act Id',
            'user_id' => 'User Id',
            'order_id' => 'Order Id',
            'order_goods_id' => 'Order Goods Id',
            'order_amount' => 'Order Amount',
            'deposit' => 'Deposit',
            'final_payment' => 'Final Payment',
            'status' => 'Status',
            'predetermined_at' => 'Predetermined At',
            'final_at' => 'Final At',
            'ship_at' => 'Ship At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}