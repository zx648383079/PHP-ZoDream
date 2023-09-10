<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class FriendModel
 * @package Domain\Model\Chat
 * @property integer $id
 * @property string $name
 * @property integer $classify_id
 * @property integer $user_id
 * @property integer $belong_id
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class FriendModel extends Model {

    protected array $append = ['user'];

    public static function tableName(): string {
        return 'chat_friend';
    }

    protected function rules(): array {
        return [
            'name' => 'string:0,50',
            'classify_id' => 'int',
            'user_id' => 'required|int',
            'belong_id' => 'required|int',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'classify_id' => 'Classify Id',
            'user_id' => 'User Id',
            'belong_id' => 'Belong Id',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
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
        $message->user_id = auth()->id();
        $message->receive_id = $this->user_id;
        $message->type = MessageModel::TYPE_TEXT;
        $message->created_at = time();
        return $message;
    }
}