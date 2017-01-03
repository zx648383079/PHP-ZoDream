<?php
namespace Domain\Model\Waste;

use Domain\Model\Model;
/**
* Class WasteModel
* @property integer $id
* @property string $code
* @property string $name
* @property string $content
* @property string $damage
* @property string $treatment
* @property integer $user_id
* @property integer $update_at
* @property integer $create_at
*/
class WasteModel extends Model {
	public static function tableName() {
        return 'waste';
    }

    protected function rules() {
		return array (
		  'code' => 'required|string:3-100',
		  'name' => 'required|string:3-200',
		  'content' => 'required',
		  'damage' => '',
		  'treatment' => '',
		  'user_id' => 'int',
		  'update_at' => 'int',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'code' => 'Code',
		  'name' => 'Name',
		  'content' => 'Content',
		  'damage' => 'Damage',
		  'treatment' => 'Treatment',
		  'user_id' => 'User Id',
		  'update_at' => 'Update At',
		  'create_at' => 'Create At',
		);
	}
}