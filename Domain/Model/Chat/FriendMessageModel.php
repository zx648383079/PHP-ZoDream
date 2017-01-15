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
 * @property integer $bonus_id11
 * @property integer $type     消息类型
 * @property integer $status
 * @property integer $update_at
 * @property integer $create_at
 */
class FriendMessageModel extends Model {
    const TYPE_TEXT = 0;
    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_VIOCE = 3;
    const TYPE_FILE = 4;
    const TYPE_BONUS = 5; //红包

    const STATUS_NONE = 0;
    const STATUS_READED = 1;  //已读
    const STATUS_RECEIVED = 2; // 接受

    public static function tableName() {
        return 'friend_message';
    }

}