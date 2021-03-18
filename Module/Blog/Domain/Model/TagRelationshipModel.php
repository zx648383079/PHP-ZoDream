<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;


/**
 * Class TagRelationshipModel
 * @package Module\Blog\Domain\Model
 * @property integer $tag_id
 * @property integer $blog_id
 * @property integer $position
 */
class TagRelationshipModel extends Model {

    protected $primaryKey = '';

	public static function tableName() {
        return 'blog_tag_relationship';
    }

    protected function rules() {
        return [
            'tag_id' => 'required|int',
            'blog_id' => 'required|int',
            'position' => 'int:0,999',
        ];
    }

    protected function labels() {
        return [
            'tag_id' => 'Tag Id',
            'blog_id' => 'Blog Id',
            'position' => 'Position',
        ];
    }

}