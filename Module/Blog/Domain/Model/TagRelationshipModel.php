<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
use Domain\Model\ModelHelper;


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

    public static function bind($blog_id, array $tags, $isNew) {
	    list($add, $_, $del) = ModelHelper::splitId(
	        $tags,
	        $isNew ? [] : static::where('blog_id', $blog_id)->pluck('tag_id'),
        );
	    if (!empty($del)) {
            static::where('blog_id', $blog_id)->whereIn('tag_id', $del)->delete();
            TagModel::query()->whereIn('id', $del)->updateDecrement('blog_count');
        }
        if (empty($add)) {
	        return;
        }
        static::query()->insert(array_map(function ($tag_id) use ($blog_id) {
            return compact('tag_id', 'blog_id');
        }, $add));
        TagModel::query()->whereIn('id', $add)->updateIncrement('blog_count', 1);
    }

}