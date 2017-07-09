<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
/**
 * Class BlogLogModel
 * @property integer $id
 * @property integer $blog_id
 * @property integer $user_id
 * @property string $action
 * @property string $content
 * @property integer $create_at
 */
class BlogLogModel extends Model {
	public static function tableName() {
        return 'blog_log';
    }

	protected function rules() {
		return [
            'blog_id' => 'required|int',
            'user_id' => 'required|int',
            'action' => 'string:3-30',
            'content' => 'string:3-45',
            'create_at' => 'int',
        ];
	}

	protected function labels() {
		return [
            'id' => 'Id',
            'blog_id' => 'Blog Id',
            'user_id' => 'User Id',
            'action' => 'Action',
            'content' => 'Content',
            'create_at' => 'Create At',
        ];
	}

}