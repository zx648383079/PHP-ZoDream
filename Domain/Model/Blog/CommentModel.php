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
* @property integer $create_at
* @property integer $parent_id
* @property integer $user_id
* @property integer $post_id
*/
class CommentModel extends Model {
	public static function tableName() {
        return 'comment';
    }
}