<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
use Zodream\Helpers\Json;

/**
 * Class FriendModel
 * @property integer $id
 * @property integer $type  消息类型
 * @property string $content
 * @property integer $item_id
 * @property integer $receive_id
 * @property integer $group_id
 * @property integer $user_id
 * @property integer $status
 * @property integer $deleted_at
 * @property integer $updated_at
 * @property integer $created_at
 * @property string $extra_rule
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

    protected array $append = ['user', 'receive'];

    public static function tableName() {
        return 'chat_message';
    }

    protected function rules() {
        return [
            'type' => 'int:0,127',
            'content' => 'required|string:0,400',
            'item_id' => 'int',
            'receive_id' => 'int',
            'group_id' => 'int',
            'user_id' => 'required|int',
            'status' => 'int:0,127',
            'deleted_at' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
            'extra_rule' => 'string:0,400',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => 'Type',
            'content' => 'Content',
            'item_id' => 'Item Id',
            'receive_id' => 'Receive Id',
            'group_id' => 'Group Id',
            'user_id' => 'User Id',
            'status' => 'Status',
            'deleted_at' => 'Deleted At',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'extra_rule' => 'Extra Rule',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function receive() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'receive_id');
    }

    public function getExtraRuleAttribute() {
        $value = $this->getAttributeValue('extra_rule');
        return empty($value) ? [] : Json::decode($value);
    }

    public function setExtraRuleAttribute($value) {
        $this->__attributes['extra_rule'] = is_array($value) ? Json::encode($value) : $value;
    }

}