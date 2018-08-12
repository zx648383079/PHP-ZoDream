<?php
namespace Module\Shop\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

/**
 * Class PaymentModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $name
 * @property integer $shipping_id
 * @property integer $price
 */
class ShippingGroupModel extends Model {
    public static function tableName() {
        return 'shop_shipping_group';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'shipping_id' => 'required|int',
            'price' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'shipping_id' => 'Shipping Id',
            'price' => 'Price',
        ];
    }

}