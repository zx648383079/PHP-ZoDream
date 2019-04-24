<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;


/**
 * Class TagModel
 * @package Module\Blog\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $blog_count
 */
class TagModel extends Model {
	public static function tableName() {
        return 'blog_tag';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,40',
            'description' => 'string:0,255',
            'blog_count' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '标签',
            'description' => '说明',
            'blog_count' => 'Blog Count',
        ];
    }

    public static function findIdByName($name) {
	    return static::where('name', $name)->value('id');
    }

    public static function getBlogByName($name) {
	    $id = static::findIdByName($name);
	    if (empty($id)) {
	        return [];
        }
        return TagRelationshipModel::where('tag_id', $id)->pluck('blog_id');
    }

}