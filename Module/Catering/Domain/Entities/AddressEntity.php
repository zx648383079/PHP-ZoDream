<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 用户收货地址
 * @property integer $id
 * @property string $name
 * @property integer $region_id
 * @property integer $user_id
 * @property string $tel
 * @property string $address
 * @property string $longitude
 * @property string $latitude
 * @property integer $updated_at
 * @property integer $created_at
 */
class AddressEntity extends Entity {
    public static function tableName(): string {
        return 'eat_address';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,30',
            'region_id' => 'required|int',
            'user_id' => 'required|int',
            'tel' => 'required|string:0,11',
            'address' => 'required|string:0,255',
            'longitude' => 'string:0,50',
            'latitude' => 'string:0,50',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'region_id' => 'Region Id',
            'user_id' => 'User Id',
            'tel' => 'Tel',
            'address' => 'Address',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}