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
 * @property float $deposit
 * @property float $final_payment
 * @property integer $status
 * @property integer $predetermined_at
 * @property integer $final_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class GroupBuyLogModel extends Model {

    public static function tableName() {
        return 'shop_group_buy_log';
    }

    protected function rules() {
        return [
            'act_id' => 'required|int',
            'user_id' => 'required|int',
            'order_id' => 'required|int',
            'order_goods_id' => 'required|int',
            'deposit' => 'string',
            'final_payment' => 'string',
            'status' => 'int:0,127',
            'predetermined_at' => 'int',
            'final_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'act_id' => 'Act Id',
            'user_id' => 'User Id',
            'order_id' => 'Order Id',
            'order_goods_id' => 'Order Goods Id',
            'deposit' => 'Deposit',
            'final_payment' => 'Final Payment',
            'status' => 'Status',
            'predetermined_at' => 'Predetermined At',
            'final_at' => 'Final At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}