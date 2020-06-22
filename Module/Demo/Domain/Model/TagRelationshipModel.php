<?php
namespace Module\Demo\Domain\Model;

use Domain\Model\Model;


/**
 * Class TagRelationshipModel
 * @property integer $tag_id
 * @property integer $post_id
 * @property integer $position
 */
class TagRelationshipModel extends Model {

    protected $primaryKey = '';

	public static function tableName() {
        return 'demo_tag_relationship';
    }

    protected function rules() {
        return [
            'tag_id' => 'required|int',
            'post_id' => 'required|int',
            'position' => 'int:0,999',
        ];
    }

    protected function labels() {
        return [
            'tag_id' => 'Tag Id',
            'post_id' => 'Blog Id',
            'position' => 'Position',
        ];
    }

    public static function bind($post_id, array $tags, $isNew) {
	    $exist = $isNew ? [] : static::where('post_id', $post_id)->pluck('tag_id');
	    $del = empty($tags) ? $exist : array_diff($exist, $tags);
	    $add = empty($exist) ? $tags : array_diff($tags, $exist);
	    if (!empty($del)) {
            static::where('post_id', $post_id)->whereIn('tag_id', $del)->delete();
            TagModel::query()->whereIn('id', $del)->updateOne('post_count', -1);
        }
        if (empty($add)) {
	        return;
        }
        static::query()->insert(array_map(function ($tag_id) use ($post_id) {
            return compact('tag_id', 'post_id');
        }, $add));
        TagModel::query()->whereIn('id', $add)->updateOne('post_count', 1);
    }

}