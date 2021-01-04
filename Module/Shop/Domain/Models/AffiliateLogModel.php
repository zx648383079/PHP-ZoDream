<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

/**
 * Class AffiliateLogModel
 * @package Module\Shop\Domain\Models
 * @property integer $id
 * @property integer $user_id
 * @property integer $item_type
 * @property integer $item_id
 * @property string $order_sn
 * @property float $order_amount
 * @property float $money
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class AffiliateLogModel extends Model {

    public static function tableName() {
        return 'shop_affiliate_log';
    }

    public function rules() {
        return [
            'user_id' => 'required|int',
            'item_type' => 'int:0,127',
            'item_id' => 'required|int',
            'order_sn' => 'string:0,30',
            'order_amount' => '',
            'money' => '',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'order_sn' => 'Order Sn',
            'order_amount' => 'Order Amount',
            'money' => 'Money',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}