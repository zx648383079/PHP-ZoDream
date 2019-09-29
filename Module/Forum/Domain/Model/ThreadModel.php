<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserModel;
use Zodream\Helpers\Time;

/**
* Class ThreadModel
 * @property integer $id
 * @property integer $forum_id
 * @property integer $classify_id
 * @property string $title
 * @property integer $user_id
 * @property integer $view_count
 * @property integer $post_count
 * @property integer $created_at
 * @property integer $updated_at
*/
class ThreadModel extends Model {
	public static function tableName() {
        return 'bbs_thread';
    }

    protected function rules() {
        return [
            'forum_id' => 'required|int',
            'classify_id' => 'int',
            'title' => 'required|string:0,200',
            'user_id' => 'required|int',
            'view_count' => 'int',
            'post_count' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'forum_id' => 'Forum Id',
            'classify_id' => 'Classify Id',
            'title' => 'Title',
            'user_id' => 'User Id',
            'view_count' => 'View Count',
            'post_count' => 'Post Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function forum() {
	    return $this->hasOne(ForumModel::class, 'id', 'forum_id');
    }

    public function classify() {
        return $this->hasOne(ForumClassifyModel::class, 'id', 'classify_id');
    }

    public function user() {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    public function getUpdatedAtAttribute() {
        return Time::isTimeAgo($this->getAttributeValue('updated_at'), 2678400);
    }

    public function getLastPostAttribute() {
        return ThreadPostModel::query()->where('thread_id', $this->id)
            ->orderBy('id', 'desc')->first();
    }
}