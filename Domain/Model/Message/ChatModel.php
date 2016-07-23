<?php
namespace Domain\Model\Message;

use Domain\Model\Model;
/**
* Class ChatModel
* @property integer $id
* @property integer $user_id
* @property integer $send_id
* @property string $content
* @property integer $create_at
*/
class ChatModel extends Model {
	public static $table = 'chat';

	protected function rules() {
		return array (
		  'user_id' => 'required|int',
		  'send_id' => 'required|int',
		  'content' => 'required',
		  'create_at' => 'required|int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'user_id' => 'User Id',
		  'send_id' => 'Send Id',
		  'content' => 'Content',
		  'create_at' => 'Create At',
		);
	}
}