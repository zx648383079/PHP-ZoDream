<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;
/**
 * Class AddressModel
 * @property integer $id
 * @property string $name
 * @property integer $region_id
 * @property integer $user_id
 * @property string $tel
 * @property string $address
 * @property integer $created_at
 * @property integer $updated_at
 */
class AddressModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_FIXED = 1;

	public static function tableName() {
        return 'shop_address';
    }


    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'region_id' => 'required|int',
            'user_id' => 'required|int',
            'tel' => 'required|string:0,11',
            'address' => 'required|string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'region_id' => 'Region Id',
            'user_id' => 'User Id',
            'tel' => 'Tel',
            'address' => 'Address',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function region() {
	    return $this->hasOne(RegionModel::class, 'id', 'region_id');
    }

//	public function save() {
//	    if (!empty($this->id) && $this->status == self::STATUS_FIXED) {
//            $this->offsetUnset('id');
//	        $this->isNewRecord = true;
//        }
//        return parent::save();
//    }

}