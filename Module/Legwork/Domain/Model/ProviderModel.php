<?php
namespace Module\Legwork\Domain\Model;

use Domain\Model\Model;

/**
 * Class ProviderModel
 * @package Module\Legwork\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $logo
 * @property string $tel
 * @property string $address
 * @property string $longitude
 * @property string $latitude
 * @property integer $overall_rating
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class ProviderModel extends Model {
    public static function tableName() {
        return 'leg_provider';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'name' => 'required|string:0,100',
            'logo' => 'string:0,255',
            'tel' => 'required|string:0,30',
            'address' => 'required|string:0,255',
            'longitude' => 'string:0,50',
            'latitude' => 'string:0,50',
            'overall_rating' => 'int:0,127',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'logo' => 'Logo',
            'tel' => 'Tel',
            'address' => 'Address',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'overall_rating' => 'Overall Rating',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}