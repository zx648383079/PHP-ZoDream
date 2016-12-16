<?php
namespace Domain\Model\Chat;

use Domain\Model\Message\MessageModel;
use Domain\Model\Model;

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
        return MessageModel::findAll([
           'user_id' => $this->user_id,
           'receive_id' => [
               $this->user_id,
               'or'
           ]
        ]);
    }
}