<?php
namespace Domain\Model\Chat;

use Domain\Model\Model;

/**
 * Class FriendGroupModel
 * @package Domain\Model\Chat
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $create_at
 */
class FriendGroupModel extends Model {
    public static function tableName() {
        return 'friend_group';
    }

    public function getFriends() {
        return $this->hasMany(FriendModel::tableName(), 'group_id');
    }
}