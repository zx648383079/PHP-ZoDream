<?php
namespace Module\Shop\Domain\Models;

use Module\Auth\Domain\Helpers;
use Module\Auth\Domain\Model\UserMetaModel;
use Module\Shop\Domain\Entities\AddressEntity;

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
class AddressModel extends AddressEntity {

    const STATUS_NONE = 0;
    const STATUS_FIXED = 1;

    public function region() {
	    return $this->hasOne(RegionModel::class, 'id', 'region_id');
    }

    public function getIsDefaultAttribute() {
	    return static::defaultId() == $this->id;
    }

    public function getHideTelAttribute() {
        return Helpers::hideTel($this->getAttributeSource('tel'));
    }

    public static function defaultId($val = 0) {
	    static $id = -1;
	    $key = 'address_id';
	    if ($id < 0) {
	        $id = intval(UserMetaModel::getVal($key));
        }
        if ($val > 0) {
	        $id > 0 ? UserMetaModel::updateVal($key, $val) : UserMetaModel::insertVal($key, $val);
	        $id = $val;
        }
        return $id;
    }

//	public function save() {
//	    if (!empty($this->id) && $this->status == self::STATUS_FIXED) {
//            $this->offsetUnset('id');
//	        $this->isNewRecord = true;
//        }
//        return parent::save();
//    }

}