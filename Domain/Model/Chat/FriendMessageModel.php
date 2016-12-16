<?php
namespace Domain\Model\Chat;

use Domain\Model\Model;

/**
 * Class FriendModel
 * @package Domain\Model\Chat
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property integer $receive_id 接收者id
 * @property integer $type     消息类型
 * @property integer $create_at
 */
class FriendMessageModel extends Model {
    public static function tableName() {
        return 'friend_message';
    }

}