<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * Class OrderGoodsModel
 * @property integer $id
 * @property integer $order_id
 * @property string $name
 * @property integer $region_id
 * @property integer $region_name
 * @property string $tel
 * @property string $address
 * @property string $best_time
 */
class OrderAddressModel extends Model {
    public static function tableName() {
        return 'shop_order_address';
    }

    protected function rules() {
        return [
            'order_id' => 'required|int',
            'name' => 'required|string:0,30',
            'region_id' => 'required|int',
            'region_name' => 'required|int',
            'tel' => 'required|string:0,11',
            'address' => 'required|string:0,255',
            'best_time' => 'string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'order_id' => 'Order Id',
            'name' => 'Name',
            'region_id' => 'Region Id',
            'region_name' => 'Region Name',
            'tel' => 'Tel',
            'address' => 'Address',
            'best_time' => 'Best Time',
        ];
    }

    public static function converter(AddressModel $address) {
        return new static([
            'name' => $address->name,
            'region_id' => $address->region_id,
            'region_name' => $address->region->full_name,
            'tel' => $address->tel,
            'address' => $address->address,
        ]);

    }
}