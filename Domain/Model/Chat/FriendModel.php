<?php
namespace Domain\Model\Chat;

use Domain\Model\Model;
use Zodream\Service\Factory;

/**
 * Class FriendModel
 * @package Domain\Model\Chat
 * @property integer $id
 * @property integer $group_id
 * @property integer $user_id
 * @property integer $name
 * @property integer $create_at
 */
class FriendModel extends Model {
    public static function tableName() {
        return 'friend';
    }

    public function getMessages() {
        return FriendMessageModel::findAll([
           'user_id' => $this->user_id,
           'receive_id' => [
               $this->user_id,
               'or'
           ]
        ]);
    }

    public function sendMessage() {
        $message = new FriendMessageModel();
        $message->user_id = Factory::user()->getId();
        $message->receive_id = $this->user_id;
        $message->type = FriendMessageModel::TYPE_TEXT;
        $message->create_at = time();
        return $message;
    }
}