<?php
namespace Domain\Model\Forum;

use Domain\Model\Model;
/**
* Class ThreadModel
* @property integer $id
* @property integer $forum_id
* @property string $title
* @property integer $readperm
* @property integer $user_id
* @property string $user_name
* @property integer $replies
* @property integer $views
* @property integer $update_at
* @property integer $update_user
* @property integer $create_at
*/
class ThreadModel extends Model {
	public static function tableName() {
        return 'thread';
    }

    protected function rules() {
		return array (
		  'forum_id' => 'required|int',
		  'title' => 'required|string:3-100',
		  'readperm' => 'int',
		  'user_id' => 'required|int',
		  'user_name' => 'required|string:3-45',
		  'replies' => 'int',
		  'views' => 'int',
		  'update_at' => 'int',
		  'update_user' => 'int',
		  'create_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'forum_id' => 'Forum Id',
		  'title' => 'Title',
		  'readperm' => 'Readperm',
		  'user_id' => 'User Id',
		  'user_name' => 'User Name',
		  'replies' => 'Replies',
		  'views' => 'Views',
		  'update_at' => 'Update At',
		  'update_user' => 'Update User',
		  'create_at' => 'Create At',
		);
	}
}