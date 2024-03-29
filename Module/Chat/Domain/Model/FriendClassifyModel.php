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
class FriendClassifyModel extends Model {
    public static function tableName(): string {
        return 'chat_friend_classify';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'user_id' => 'required|int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
        ];
    }

    public function users() {
        return $this->hasMany(FriendModel::class, 'classify_id')
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