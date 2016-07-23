<?php
namespace Domain\Model\Home;

use Domain\Model\Model;
/**
* Class TalkModel
* @property integer $id
* @property string $content
* @property integer $user_id
* @property integer $create_at
*/
class TalkModel extends Model {
	public static $table = 'talk';
	

	protected function rules() {
		return array (
		  'content' => 'required|string:3-255',
		  'user_id' => 'required|int',
		  'create_at' => 'required|int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'content' => 'Content',
		  'user_id' => 'User Id',
		  'create_at' => 'Create At',
		);
	}
}