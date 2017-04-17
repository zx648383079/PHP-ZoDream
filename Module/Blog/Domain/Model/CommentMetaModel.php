<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
/**
 * Class CommentModel
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property integer $comment_id
 */
class CommentMetaModel extends Model {
	public static function tableName() {
        return 'comment_meta';
    }

}