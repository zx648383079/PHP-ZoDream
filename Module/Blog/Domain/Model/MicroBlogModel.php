<?php
namespace Module\Blog\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserModel;
use Zodream\Database\Query\Query;
use Zodream\Helpers\Time;


/**
* Class MicroBlogModel
 * @property integer $id
 * @property string $content
 * @property integer $user_id
 * @property integer $recommend
 * @property integer $created_at
 * @property integer $updated_at
*/
class MicroBlogModel extends Model {
	public static function tableName() {
        return 'blog_micro';
    }

    protected function rules() {
        return [
            'content' => 'required|string:0,140',
            'user_id' => 'int',
            'recommend' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'content' => 'Content',
            'user_id' => 'User Id',
            'recommend' => 'Recommend',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    /**
     * 不允许频繁发布
     * @return bool
     * @throws \Exception
     */
    public static function canPublish() {
	    $time = static::where('user_id', auth()->id())->max('created_at');
	    return !$time || $time < time() - 300;
    }

    public static function canRecommend($id) {
        return BlogLogModel::where([
                'user_id' => auth()->id(),
                'type' => BlogLogModel::TYPE_BLOG_MICRO,
                'id_value' => $id,
                'action' => BlogLogModel::ACTION_RECOMMEND
            ])->count() < 1;
    }

    /**
     * 推荐
     * @return bool
     * @throws \Exception
     */
    public function recommendThis() {
        $this->recommend++;
        if (!$this->save()) {
            return false;
        }
        return !!BlogLogModel::create([
            'type' => BlogLogModel::TYPE_BLOG_MICRO,
            'action' => BlogLogModel::ACTION_RECOMMEND,
            'id_value' => $this->id,
            'user_id' => auth()->id()
        ]);
    }
}