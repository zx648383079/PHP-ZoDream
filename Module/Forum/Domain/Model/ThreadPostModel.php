<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;
/**
* Class ThreadPostModel
* @property integer $id
* @property integer $forum_id
* @property integer $thread_id
* @property string $content
* @property integer $first
* @property integer $user_id
* @property string $user_name
* @property string $ip
* @property integer $create_at
*/
class ThreadPostModel extends Model {
	public static function tableName() {
        return 'bbs_thread_post';
    }

    protected function rules() {
		return array (
		  'forum_id' => 'required|int',
		  'thread_id' => 'required|int',
		  'content' => 'required',
		  'first' => 'int:0-1',
		  'user_id' => 'required|int',
		  'user_name' => 'required|string:3-30',
		  'ip' => 'string:3-20',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'forum_id' => 'Forum Id',
		  'thread_id' => 'Thread Id',
		  'content' => 'Content',
		  'first' => 'First',
		  'user_id' => 'User Id',
		  'user_name' => 'User Name',
		  'ip' => 'Ip',
		  'create_at' => 'Create At',
		);
	}
}