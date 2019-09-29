<?php
namespace Module\Forum\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserModel;
use Zodream\Helpers\Time;

/**
* Class ThreadPostModel
 * @property integer $id
 * @property string $content
 * @property integer $thread_id
 * @property integer $user_id
 * @property string $ip
 * @property integer $grade
 * @property integer $created_at
 * @property integer $updated_at
*/
class ThreadPostModel extends Model {
	public static function tableName() {
        return 'bbs_thread_post';
    }

    protected function rules() {
        return [
            'content' => 'required|string:0,255',
            'thread_id' => 'required|int',
            'user_id' => 'required|int',
            'ip' => 'required|string:0,120',
            'grade' => 'int:0,999999',
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    public function getUpdatedAtAttribute() {
        return Time::isTimeAgo($this->getAttributeValue('updated_at'), 2678400);
    }

}