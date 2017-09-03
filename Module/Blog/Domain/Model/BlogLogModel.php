<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
/**
 * Class BlogLogModel
 * @property integer $id
 * @property integer $type
 * @property integer $id_value
 * @property integer $user_id
 * @property string $action
 * @property integer $created_at
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
            'created_at' => 'int',
        ];
	}

	protected function labels() {
		return [
            'id' => 'Id',
            'blog_id' => 'Blog Id',
            'user_id' => 'User Id',
            'action' => 'Action',
            'created_at' => 'Create At',
        ];
	}

}