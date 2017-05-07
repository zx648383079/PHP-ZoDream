<?php
namespace Module\Address\Domain\Model;
use Domain\Model\Model;
/**
 * Class AddressModel
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $tel
 * @property integer $region_id
 * @property string $address
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 */
class AddressModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_FIXED = 1;

	public static function tableName() {
        return 'address';
    }

	protected $primaryKey = [
        'id',
    ];

	protected function rules() {
		return [
            'user_id' => 'required|int',
            'name' => 'string:3-45',
            'tel' => 'string:3-20',
            'region_id' => 'int',
            'address' => 'string:3-200',
            'status' => 'int:0-1',
            'create_at' => 'int',
            'update_at' => 'int',
        ];
	}

	protected function labels() {
		return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'tel' => 'Tel',
            'region_id' => 'Region Id',
            'address' => 'Address',
            'status' => 'Status',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
	}

	public function save() {
	    if (!empty($this->id) && $this->status == self::STATUS_FIXED) {
            $this->offsetUnset('id');
	        $this->isNewRecord = true;
        }
        return parent::save();
    }

}