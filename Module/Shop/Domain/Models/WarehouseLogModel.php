<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class WarehouseLogModel
 * @package Module\Shop\Domain\Models
 * @property integer $id
 * @property integer $warehouse_id
 * @property integer $goods_id
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $amount
 * @property integer $order_id
 * @property string $remark
 * @property integer $created_at
 */
class WarehouseLogModel extends Model {

    public static function tableName() {
        return 'shop_warehouse_log';
    }

    protected function rules() {
        return [
            'warehouse_id' => 'required|int',
            'goods_id' => 'required|int',
            'user_id' => 'required|int',
            'product_id' => 'int',
            'amount' => 'required|int',
            'order_id' => 'int',
            'remark' => 'string',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'warehouse_id' => 'Warehouse Id',
            'user_id' => 'User Id',
            'goods_id' => 'Goods Id',
            'product_id' => 'Product Id',
            'amount' => 'Amount',
            'order_id' => 'Order Id',
            'remark' => 'Remark',
            'created_at' => 'Created At',
        ];
    }

    public function warehouse() {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouse_id');
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function goods() {
        return $this->hasOne(GoodsSimpleModel::class, 'id', 'goods_id');
    }
}