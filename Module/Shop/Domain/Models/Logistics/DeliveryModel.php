<?php
namespace Module\Shop\Domain\Models\Logistics;


use Domain\Model\Model;
use Module\Shop\Domain\Models\OrderGoodsModel;
use Module\Shop\Domain\Models\OrderModel;
use Module\Shop\Domain\Models\ShippingModel;

/**
 * Class DeliveryModel
 * @package Module\Shop\Domain\Model\Logistics
 * @property integer $id
 * @property integer $user_id
 * @property integer $order_id
 * @property integer $status
 * @property integer $shipping_id
 * @property string $shipping_name
 * @property float $shipping_fee
 * @property string $logistics_number
 * @property string $name
 * @property integer $region_id
 * @property string $region_name
 * @property string $tel
 * @property string $address
 * @property string $best_time
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $logistics_content
 */
class DeliveryModel extends Model {
    public static function tableName() {
        return 'shop_delivery';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'order_id' => 'required|int',
            'status' => 'int',
            'shipping_id' => 'int',
            'shipping_name' => 'string:0,30',
            'shipping_fee' => '',
            'logistics_number' => 'string:0,30',
            'logistics_content' => '',
            'name' => 'required|string:0,30',
            'region_id' => 'required|int',
            'region_name' => 'required|string:0,255',
            'tel' => 'required|string:0,11',
            'address' => 'required|string:0,255',
            'best_time' => 'string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'order_id' => 'Order Id',
            'status' => 'Status',
            'shipping_id' => 'Shipping Id',
            'shipping_name' => 'Shipping Name',
            'shipping_fee' => 'Shipping Fee',
            'logistics_number' => 'Logistics Number',
            'logistics_content' => '物流信息',
            'name' => 'Name',
            'region_id' => 'Region Id',
            'region_name' => 'Region Name',
            'tel' => 'Tel',
            'address' => 'Address',
            'best_time' => 'Best Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function createByOrder(OrderModel $order, $logistics_number, $shipping_id = 0) {
        if ($shipping_id > 0) {
            $order->shipping_id = $shipping_id;
            $order->shipping_name = ShippingModel::where('id', $shipping_id)->value('name');
        }
        $data = [
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'status' => 0,
            'shipping_id' => $order->shipping_id,
            'shipping_name' => $order->shipping_name,
            'shipping_fee' => $order->shipping_fee,
            'logistics_number' => $logistics_number,
            'name' => '',
            'region_id' => 0,
            'region_name' => '',
            'tel' => '',
            'address' => '',
            'best_time' => '',
            'created_at' => time(),
            'updated_at' => time(),
        ];
        if ($order->address) {
            $data['name'] = $order->address->name;
            $data['region_id'] = $order->address->region_id;
            $data['region_name'] = $order->address->region_name;
            $data['tel'] = $order->address->tel;
            $data['address'] = $order->address->address;
            $data['best_time'] = $order->address->best_time;
        }
        $model = static::create($data);
        if (empty($model)) {
            return false;
        }
        foreach ($order->goods as $goods) {
            /** @var $goods OrderGoodsModel */
            DeliveryGoodsModel::create([
                'delivery_id' => $model->id,
                'order_goods_id' => $goods->id,
                'goods_id' => $goods->goods_id,
                'name' => $goods->name,
                'thumb' => $goods->thumb,
                'series_number' => $goods->series_number,
                'amount' => $goods->amount,
                'product_id' => $goods->product_id,
                'type_remark' => $goods->type_remark,
            ]);
        }
        return true;
    }
}