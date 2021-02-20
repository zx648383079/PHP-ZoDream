<?php
namespace Module\Shop\Domain\Models;


use Domain\Model\Model;

/**
 * Class WarehouseModel
 * @package Module\Shop\Domain\Models
 * @property integer $id
 * @property string $name
 * @property string $tel
 * @property string $link_user
 * @property string $address
 * @property string $longitude
 * @property string $latitude
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class WarehouseModel extends Model {

    public static function tableName() {
        return 'shop_warehouse';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'tel' => 'required|string:0,30',
            'link_user' => 'string:0,30',
            'address' => 'string:0,255',
            'longitude' => 'string:0,50',
            'latitude' => 'string:0,50',
            'remark' => 'string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'tel' => 'Tel',
            'link_user' => 'Link User',
            'address' => 'Address',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}