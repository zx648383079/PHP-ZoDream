<?php
namespace Module\Demo\Domain\Model;

use Domain\Model\Model;

/**
 * Class TagModel
 * @package Module\Blog\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $post_count
 */
class TagModel extends Model {
	public static function tableName() {
        return 'demo_tag';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,40',
            'description' => 'string:0,255',
            'post_count' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '标签',
            'description' => '说明',
            'post_count' => 'Post Count',
        ];
    }

    public static function findIdByName($name) {
	    return static::where('name', $name)->value('id');
    }

    public static function getPostByName($name) {
	    $id = static::findIdByName($name);
	    if (empty($id)) {
	        return [];
        }
        return TagRelationshipModel::where('tag_id', $id)->pluck('post_id');
    }

}