<?php
namespace Domain\Model\Message;

use Domain\Model\Model;
/**
* Class MessageModel
* @property integer $id
* @property integer $user_id
* @property integer $send_id
* @property string $content
* @property integer $read
* @property integer $create_at
*/
class MessageModel extends Model {
	public static $table = 'message';

	protected function rules() {
		return array (
		  'user_id' => 'required|int',
		  'send_id' => 'required|int',
		  'content' => '',
		  'read' => 'int:0-1',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'user_id' => 'User Id',
		  'send_id' => 'Send Id',
		  'content' => 'Content',
		  'read' => 'Read',
		  'create_at' => 'Create At',
		);
	}
}