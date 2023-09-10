<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Models;


use Domain\Concerns\ExtraRule;
use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;

/**
 * Class MessageModel
 * @package Module\OnlineService\Domain\Models
 * @property integer $id
 * @property integer $user_id
 * @property integer $session_id
 * @property integer $send_type
 * @property integer $type
 * @property string $content
 * @property string $extra_rule
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class MessageModel extends Model {

    use ExtraRule;
    const TYPE_TEXT = 0;
    const TYPE_EMOJI = 1;
    const TYPE_IMAGE = 2;

    public static function tableName(): string {
        return 'service_message';
    }

    protected function rules(): array {
        return [
            'user_id' => 'int',
            'session_id' => 'required|int',
            'send_type' => 'int:0,2',
            'type' => 'int:0,127',
            'content' => 'string:0,255',
            'extra_rule' => 'string:0,255',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'session_id' => 'Session Id',
            'send_type' => '发送者身份',
            'type' => 'Type',
            'content' => 'Content',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }
}