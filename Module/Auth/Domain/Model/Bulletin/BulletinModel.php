<?php
namespace Module\Auth\Domain\Model\Bulletin;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;


/**
 * Class BulletinModel
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $user_id
 * @property integer $create_at
 * @property integer $delete_at
 * @property UserSimpleModel $user
 */
class BulletinModel extends Model {

    protected array $append = ['user', 'user_name', 'icon'];

	public static function tableName() {
        return 'bulletin';
    }

    protected function rules() {
        return [
            'title' => 'required|string:0,100',
            'content' => 'required|string:0,255',
            'type' => 'int:0,99',
            'user_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => 'Title',
            'content' => 'Content',
            'type' => 'Type',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
	    return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function getUserNameAttribute() {
	    if ($this->user_id < 1) {
	        return '[系统通知]';
        }
	    if ($this->user) {
            return $this->user->name;
        }
        return '[用户已删除]';
    }

    public function getIconAttribute() {
        if ($this->user_id < 1) {
            return '系';
        }
        if ($this->user) {
            return mb_substr($this->user->name, 0, 1);
        }
        return 'NULL';
    }

    /**
     * SEND MESSAGE TO ANY USERS
     *
     * @param array|string $user
     * @param string $title
     * @param string $content
     * @param int $type
     * @return bool|int
     * @throws \Exception
     */
	public static function message($user, $title, $content, $type = 0) {
        return static::send($user, htmlspecialchars($title), htmlspecialchars($content), $type, auth()->id());
	}

    public static function system($user, $title, $content, $type = 99) {
        return static::send($user, $title, $content, $type, 0);
    }

    public static function send($user, $title, $content, $type = 0, $sender = 0) {
        $bulletin = new static();
        $bulletin->title = $title;
        $bulletin->content = $content;
        $bulletin->type = $type;
        $bulletin->create_at = time();
        $bulletin->user_id = $sender;
        if (!$bulletin->save()) {
            return false;
        }
        $data = [];
        foreach ((array)$user as $item) {
            $data[] = [$bulletin->id, $item, time()];
        }
        return BulletinUserModel::query()->insert(['bulletin_id', 'user_id', 'created_at'], $data);
    }

    /**
     * 获取未读消息
     * @return int
     * @throws \Exception
     */
    public static function unreadCount() {
	    if (auth()->guest()) {
	        return 0;
        }
	    return BulletinUserModel::where('user_id', auth()->id())->where('status', 0)->count();
    }
}