<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
use Zodream\Helpers\Time;

/**
* Class ThreadPostModel
 * @property integer $id
 * @property string $content
 * @property integer $thread_id
 * @property integer $user_id
 * @property string $ip
 * @property integer $grade
 * @property integer $status
 * @property integer $is_invisible
 * @property integer $agree_count
 * @property integer $disagree_count
 * @property integer $created_at
 * @property integer $updated_at
 * @property ThreadModel $thread
*/
class ThreadPostModel extends Model {

    protected array $hidden = ['ip'];

	public static function tableName() {
        return 'bbs_thread_post';
    }

    protected function rules() {
        return [
            'content' => 'required|string',
            'thread_id' => 'required|int',
            'user_id' => 'required|int',
            'ip' => 'required|string:0,120',
            'grade' => 'int',
            'is_invisible' => 'int:0,9',
            'agree_count' => 'int',
            'disagree_count' => 'int',
            'status' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'content' => 'Content',
            'thread_id' => 'Thread Id',
            'user_id' => 'User Id',
            'ip' => 'Ip',
            'grade' => '层级',
            'is_invisible' => '是否通过审核',
            'agree_count' => 'Agree Count',
            'disagree_count' => 'Disagree Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function thread() {
        return $this->hasOne(ThreadModel::class, 'id', 'thread_id');
    }

    public function canRemove() {
        if (auth()->guest()) {
            return false;
        }
        return auth()->id() == $this->user_id;
    }

    public function getIsPublicPostAttribute() {
	    if (auth()->guest()) {
	        return !$this->thread->is_private_post;
        }
	    $user_id = auth()->id();
	    return !$this->thread->is_private_post
            || $this->user_id == $user_id || $this->thread->user_id == $user_id;
    }

}