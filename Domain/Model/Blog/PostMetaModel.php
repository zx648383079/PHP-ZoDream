<?php
namespace Domain\Model\Blog;

use Domain\Model\Model;
/**
 * Class CommentModel
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property integer $comment_id
 */
class PostMetaModel extends Model {
	public static function tableName() {
        return 'postmeta';
    }

}