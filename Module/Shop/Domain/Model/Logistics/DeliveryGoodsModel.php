<?php
namespace Module\Shop\Domain\Model\Logistics;


use Domain\Model\Model;

/**
 * Class DeliveryGoodsModel
 * @package Module\Shop\Domain\Model\Logistics
 * @property integer $id
 * @property integer $delivery_id
 * @property integer $order_goods_id
 * @property integer $goods_id
 * @property string $name
 * @property string $series_number
 * @property integer $amount
 */
class DeliveryGoodsModel extends Model {
    public static function tableName() {
        return 'shop_delivery_goods';
    }

    protected function rules() {
        return [
            'delivery_id' => 'required|int',
            'order_goods_id' => 'required|int',
            'goods_id' => 'required|int',
            'name' => 'required|string:0,100',
            'series_number' => 'required|string:0,100',
            'amount' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'delivery_id' => 'Delivery Id',
            'order_goods_id' => 'Order Goods Id',
            'goods_id' => 'Goods Id',
            'name' => 'Name',
            'series_number' => 'Series Number',
            'amount' => 'Amount',
        ];
    }

}