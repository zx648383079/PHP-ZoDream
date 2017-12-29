<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;

/**
 * Class FriendGroupModel
 * @package Domain\Model\Chat
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $created_at
 */
class FriendGroupModel extends Model {
    public static function tableName() {
        return 'friend_group';
    }

    public function getFriends() {
        return $this->hasMany(FriendModel::tableName(), 'group_id');
    }
}