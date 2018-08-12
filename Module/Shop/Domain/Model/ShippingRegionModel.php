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
 * @property integer $group_id
 * @property integer $region_id
 */
class ShippingRegionModel extends Model {
    public static function tableName() {
        return 'shop_shipping_region';
    }

    protected function rules() {
        return [
            'group_id' => 'required|int',
            'region_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'group_id' => 'Group Id',
            'region_id' => 'Region Id',
        ];
    }
}