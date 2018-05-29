<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;
/**
* Class ForumModel
 * @property integer $id
 * @property string $name
 * @property string $thumb
 * @property string $description
 * @property integer $parent_id
 * @property integer $thread_count
 * @property integer $post_count
 * @property integer $type
 * @property integer $position
 * @property integer $created_at
 * @property integer $updated_at
*/
class ForumModel extends Model {
	public static function tableName() {
        return 'bbs_forum';
    }


    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'thumb' => 'string:0,100',
            'description' => 'string:0,255',
            'parent_id' => 'int',
            'thread_count' => 'int',
            'post_count' => 'int',
            'type' => 'int:0,99',
            'position' => 'int:0,999',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'thumb' => 'Thumb',
            'description' => 'Description',
            'parent_id' => 'Parent Id',
            'thread_count' => 'Thread Count',
            'post_count' => 'Post Count',
            'type' => 'Type',
            'position' => 'Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }



    public function children() {
	    return $this->hasMany(static::class, 'parent_id', 'id');
    }
}