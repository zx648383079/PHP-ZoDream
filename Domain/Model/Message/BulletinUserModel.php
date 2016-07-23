<?php
namespace Domain\Model\Message;

use Domain\Model\Model;
/**
* Class BulletinUserModel
* @property integer $bulletin_id
* @property integer $user_id
* @property integer $read
* @property integer $update_at
*/
class BulletinUserModel extends Model {
	public static $table = 'bulletin_user';

	protected $primaryKey = array ();

	protected function rules() {
		return array (
		  'bulletin_id' => 'required|int',
		  'user_id' => 'required|int',
		  'read' => 'int:0-1',
		  'update_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'bulletin_id' => 'Bulletin Id',
		  'user_id' => 'User Id',
		  'read' => 'Read',
		  'update_at' => 'Update At',
		);
	}
}