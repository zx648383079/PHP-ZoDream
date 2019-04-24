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

    public static function bind($blog_id, array $tags, $isNew) {
	    $exist = $isNew ? [] : static::where('blog_id', $blog_id)->pluck('tag_id');
	    $del = empty($tags) ? $exist : array_diff($exist, $tags);
	    $add = empty($exist) ? $tags : array_diff($tags, $exist);
	    if (!empty($del)) {
            static::where('blog_id', $blog_id)->whereIn('tag_id', $del)->delete();
            TagModel::query()->whereIn('tag_id', $del)->updateOne('blog_count', -1);
        }
        if (empty($add)) {
	        return;
        }
        static::query()->insert(array_map(function ($tag_id) use ($blog_id) {
            return compact('tag_id', 'blog_id');
        }, $add));
        TagModel::query()->whereIn('tag_id', $add)->updateOne('blog_count', 1);
    }

}