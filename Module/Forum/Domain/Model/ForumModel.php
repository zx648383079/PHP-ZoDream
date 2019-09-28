<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;
use Zodream\Html\Tree;
use Zodream\Helpers\Tree as TreeHelper;

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

    public function getTodayCountAttribute() {
	    if ($this->thread_count < 1) {
	        return 0;
        }
	    $time = strtotime(date('Y-m-d'));
	    return ThreadModel::where('forum_id', $this->id)
            ->where('created_at', '>=',  $time)
            ->where('created_at', '<=',  $time + 86400)->count();
    }

    public function lastThread() {
	    return $this->hasOne(ThreadModel::class, 'forum_id', 'id');
    }

    public static function tree() {
        return new Tree(static::cacheAll());
    }

    /**
     * @return static[]
     * @throws \Exception
     */
    public static function cacheAll() {
	    static $data;
	    if (!empty($data)) {
	        return $data;
        }
        return $data = static::query()->all();
    }

    public static function findById($id) {
	    foreach (static::cacheAll() as $item) {
	        if ($item->id == $id) {
	            return $item;
            }
        }
        return null;
    }

    public static function getAllChildrenId($id) {
        $data = TreeHelper::getTreeChild(static::cacheAll(), $id);
        $data[] = $id;
        return $data;
    }

    public static function findChildren($id) {
        $data = [];
        foreach (static::cacheAll() as $item) {
            if ($item->parent_id == $id) {
                $data[] = $item;
            }
        }
        return $data;
    }

    /**
     * @param $id
     * @return static[]
     * @throws \Exception
     */
    public static function findPath($id) {
        $path = TreeHelper::getTreeParent(static::cacheAll(), $id);
        $data = [];
        foreach ($path as $id) {
            $item = static::findById($id);
            if (!empty($item)) {
                $data[] = $item;
            }
        }
        return $data;
    }
}