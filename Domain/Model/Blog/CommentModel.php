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

    public static function getChildren($postId, $parentId = 0) {
	    $data = static::find()->alias('c')->left('user u', ['u.id' => 'c.user_id'])
            ->where(['c.post_id' => $postId, 'c.parent_id' => $parentId])
            ->order('c.create_at desc')->select([

            ])->asArray()->all();
	    foreach ($data as &$item) {
	        $item['children'] = static::getChildren($postId, $item['id']);
        }
        return $data;
    }

    public static function getAll($postId) {
        $data = static::find()->alias('c')->left('user u', ['u.id' => 'c.user_id'])
            ->where(['c.post_id' => $postId])
            ->order('c.parent_id asc,c.create_at desc')->select([

            ])->asArray()->all();
        return static::getChildrenByData(0, $data);
    }

    protected static function getChildrenByData($parentId, array $args) {
	    $data = [];
	    foreach ($args as $item) {
	        if ($item['parent_id'] == $parentId) {
	            $item['children'] = static::getChildrenByData($item['id'], $args);
	            $data[] = $item;
            }
        }
        return $data;
    }
}