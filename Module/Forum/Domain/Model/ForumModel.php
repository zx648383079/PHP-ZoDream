<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;
use Zodream\Html\Tree;

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
            'name' => '名称',
            'thumb' => '图片',
            'description' => '简介',
            'parent_id' => '上级',
            'thread_count' => 'Thread Count',
            'post_count' => 'Post Count',
            'type' => 'Type',
            'position' => '排序',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function children() {
	    return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public static function tree() {
        return new Tree(static::query()->all());
    }
}