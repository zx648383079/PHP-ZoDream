<?php
namespace Domain\Model\Blog;

use Domain\Model\Model;
/**
* Class PostModel
* @property integer $id
* @property string $title
* @property string $content
* @property integer $user_id
* @property integer $update_at
* @property integer $create_at
* @property string $excerpt
* @property string $status
* @property string $comment_status
* @property string $ping_status
* @property string $password
* @property string $name
* @property string $to_ping
* @property string $pinged
* @property integer $parent
* @property string $guid
* @property integer $position
* @property string $type
* @property string $mime_type
* @property integer $comment_count
* @property integer $recommend
*/
class PostModel extends Model {
	public static $table = 'post';

	protected function rules() {
		return array (
		  'title' => 'required|string:3-255',
		  'content' => 'required',
		  'user_id' => 'required|int',
		  'update_at' => '|int',
		  'create_at' => '|int',
		  'excerpt' => '',
		  'status' => '|string:3-20',
		  'comment_status' => '|string:3-20',
		  'ping_status' => '|string:3-20',
		  'password' => '|string:3-20',
		  'name' => '|string:3-200',
		  'to_ping' => '',
		  'pinged' => '',
		  'parent' => '|int',
		  'guid' => '|string:3-255',
		  'position' => '|int',
		  'type' => '|string:3-20',
		  'mime_type' => '|string:3-20',
		  'comment_count' => '|int',
		  'recommend' => '|int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'title' => 'Title',
		  'content' => 'Content',
		  'user_id' => 'User Id',
		  'update_at' => 'Update At',
		  'create_at' => 'Create At',
		  'excerpt' => 'Excerpt',
		  'status' => 'Status',
		  'comment_status' => 'Comment Status',
		  'ping_status' => 'Ping Status',
		  'password' => 'Password',
		  'name' => 'Name',
		  'to_ping' => 'To Ping',
		  'pinged' => 'Pinged',
		  'parent' => 'Parent',
		  'guid' => 'Guid',
		  'position' => 'Position',
		  'type' => 'Type',
		  'mime_type' => 'Mime Type',
		  'comment_count' => 'Comment Count',
		  'recommend' => 'Recommend',
		);
	}

	public function getNextAndBefore($id = null) {
		if (empty($id)) {
			$id = $this->id;
		}
		$before = $this->find()->where(array(
			'id < '.$id,
			'status' => array(
				'in',
				array(
					'publish',
					'password'
				)
			)))->order('id desc')->select('id,title')->one();
		return array(
			$before,
			$this->find()->where(array(
				'id > '.$id,
				'status' => array(
					'in',
					array(
						'publish',
						'password'
					)
				)
			))->select('id,title')->one()
		);
	}
}