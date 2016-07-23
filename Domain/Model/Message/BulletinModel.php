<?php
namespace Domain\Model\Message;

use Domain\Model\Model;
/**
* Class BulletinModel
* @property integer $id
* @property string $title
* @property string $content
* @property integer $type
* @property integer $create_at
* @property integer $delete_at
*/
class BulletinModel extends Model {
	public static $table = 'bulletin';

	protected function rules() {
		return array (
		  'title' => 'required|string:3-100',
		  'content' => '',
		  'type' => 'int',
		  'create_at' => 'int',
		  'delete_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'title' => 'Title',
		  'content' => 'Content',
		  'type' => 'Type',
		  'create_at' => 'Create At',
		  'delete_at' => 'Delete At',
		);
	}
}