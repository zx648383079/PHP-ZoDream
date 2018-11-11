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
 * @property integer $id
 * @property integer $shipping_id
 * @property float $first_step
 * @property float $first_fee
 * @property float $additional
 * @property float $additional_fee
 * @property float $free_step
 */
class ShippingGroupModel extends Model {
    public static function tableName() {
        return 'shop_shipping_group';
    }

    protected function rules() {
        return [
            'shipping_id' => 'required|int',
            'first_step' => '',
            'first_fee' => '',
            'additional' => '',
            'additional_fee' => '',
            'free_step' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'shipping_id' => 'Shipping Id',
            'first_step' => '首件/首重',
            'first_fee' => '运费',
            'additional' => '续件/续重',
            'additional_fee' => '续费',
            'free_step' => '免费标准',
        ];
    }

}