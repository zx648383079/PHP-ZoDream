<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;
use Zodream\Service\Factory;

/**
 * Class FriendModel
 * @package Domain\Model\Chat
 * @property integer $id
 * @property string $name
 * @property integer $group_id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class FriendModel extends Model {
    public static function tableName() {
        return 'chat_friend';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'group_id' => 'required|int',
            'user_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'group_id' => 'Group Id',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getMessages() {
        return MessageModel::findAll([
           'user_id' => $this->user_id,
           'receive_id' => [
               $this->user_id,
               'or'
           ]
        ]);
    }

    public function sendMessage() {
        $message = new MessageModel();
        $message->user_id = Factory::user()->getId();
        $message->receive_id = $this->user_id;
        $message->type = MessageModel::TYPE_TEXT;
        $message->created_at = time();
        return $message;
    }
}