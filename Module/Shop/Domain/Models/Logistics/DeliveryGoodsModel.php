<?php
namespace Module\Shop\Domain\Models\Logistics;


use Domain\Model\Model;

/**
 * Class DeliveryGoodsModel
 * @package Module\Shop\Domain\Model\Logistics
 * @property integer $id
 * @property integer $delivery_id
 * @property integer $order_goods_id
 * @property integer $goods_id
 * @property integer $product_id
 * @property string $name
 * @property string $series_number
 * @property integer $amount
 * @property string $thumb
 * @property string $type_remark
 */
class DeliveryGoodsModel extends Model {
    public static function tableName() {
        return 'shop_delivery_goods';
    }

    public function rules() {
        return [
            'delivery_id' => 'required|int',
            'order_goods_id' => 'required|int',
            'product_id' => 'int',
            'goods_id' => 'required|int',
            'name' => 'required|string:0,100',
            'thumb' => '',
            'series_number' => 'required|string:0,100',
            'amount' => 'int',
            'type_remark' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'delivery_id' => 'Delivery Id',
            'order_goods_id' => 'Order Goods Id',
            'goods_id' => 'Goods Id',
            'product_id' => 'product Id',
            'name' => 'Name',
            'thumb' => 'Thumb',
            'series_number' => 'Series Number',
            'amount' => 'Amount',
            'type_remark' => 'type_remark',
        ];
    }

}