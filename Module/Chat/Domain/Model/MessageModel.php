<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;

/**
 * Class FriendModel
 * @property integer $id
 * @property integer $type  消息类型
 * @property string $content
 * @property integer $receive_id
 * @property integer $group_id
 * @property integer $user_id
 * @property integer $status
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class MessageModel extends Model {

    const TYPE_TEXT = 0;
    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_VOICE = 3;
    const TYPE_FILE = 4;
    const TYPE_BONUS = 5; //红包

    const STATUS_NONE = 0;
    const STATUS_READ = 1;  //已读
    const STATUS_RECEIVED = 2; // 接受

    public static function tableName() {
        return 'chat_message';
    }

    protected function rules() {
        return [
            'type' => 'int:0,99',
            'content' => 'required|string:0,200',
            'receive_id' => 'int',
            'group_id' => 'int',
            'user_id' => 'required|int',
            'status' => 'int:0,9',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => 'Type',
            'content' => 'Content',
            'receive_id' => 'Receive Id',
            'group_id' => 'Group Id',
            'user_id' => 'User Id',
            'status' => 'Status',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}