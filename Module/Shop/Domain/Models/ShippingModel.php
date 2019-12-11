<?php
namespace Module\Shop\Domain\Models;

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
 * @property string $name
 * @property string $code
 * @property integer $method
 * @property string $icon
 * @property string $description
 * @property integer $position
 * @property integer $created_at
 * @property integer $updated_at
 */
class ShippingModel extends Model {
    public static function tableName() {
        return 'shop_shipping';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'code' => 'required|string:0,30',
            'method' => 'int:0,99',
            'icon' => 'string:0,100',
            'description' => 'string:0,255',
            'position' => 'int:0,99',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'code' => 'Code',
            'method' => '计费方式',
            'icon' => '图标',
            'description' => '介绍',
            'position' => '排序',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return float
     */
    public function getFee() {
        return 0;
    }

    public function canUsePayment(PaymentModel $payment) {
        return true;
    }

    /**
     * @param AddressModel $address
     * @return static[]
     */
    public static function getByAddress(AddressModel $address) {
        return static::all();
    }
}