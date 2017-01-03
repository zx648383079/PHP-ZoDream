<?php
namespace Domain\Model\Shopping;


use Domain\Model\Model;

/**
 * Class AddressModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $user_id
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $street
 * @property string $address
 * @property string $consignee
 * @property string $telphone
 * @property integer $is_default
 */
class AddressModel extends Model {
    public static function tableName() {
        return 'address';
    }

    public function isDefault() {
        return $this->is_default === 1;
    }
}