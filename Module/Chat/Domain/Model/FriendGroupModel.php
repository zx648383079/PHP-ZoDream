<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;


/**
 * Class FriendGroupModel
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $created_at
 */
class FriendGroupModel extends Model {
    public static function tableName() {
        return 'chat_friend_group';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,100',
            'user_id' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
        ];
    }

    public function users() {
        return $this->hasMany(FriendModel::class, 'group_id')
            ->with('user')
            ->where('belong_id', auth()->id());
    }


    public function getCountAttribute() {
        return count($this->users);
    }

    public function getOnlineCountAttribute() {
        return 0;
    }
}