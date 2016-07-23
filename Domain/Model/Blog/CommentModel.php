<?php
namespace Domain\Model\Blog;

use Domain\Model\Model;
/**
* Class CommentModel
* @property integer $id
* @property string $content
* @property string $name
* @property string $email
* @property string $url
* @property string $ip
* @property integer $create_at
* @property integer $karma
* @property string $approved
* @property string $agent
* @property string $type
* @property integer $parent
* @property integer $user_id
* @property integer $post_id
*/
class CommentModel extends Model {
	public static $table = 'comment';

	protected function rules() {
		return array (
		  'content' => 'required',
		  'name' => 'string:3-45',
		  'email' => 'string:3-100',
		  'url' => 'string:3-200',
		  'ip' => 'string:3-20',
		  'create_at' => 'int',
		  'karma' => 'int',
		  'approved' => 'string:3-20',
		  'agent' => 'string:3-255',
		  'type' => 'string:3-20',
		  'parent' => 'int',
		  'user_id' => 'int',
		  'post_id' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'content' => 'Content',
		  'name' => 'Name',
		  'email' => 'Email',
		  'url' => 'Url',
		  'ip' => 'Ip',
		  'create_at' => 'Create At',
		  'karma' => 'Karma',
		  'approved' => 'Approved',
		  'agent' => 'Agent',
		  'type' => 'Type',
		  'parent' => 'Parent',
		  'user_id' => 'User Id',
		  'post_id' => 'Post Id',
		);
	}
}