<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Models;


use Domain\Model\Model;

/**
 * Class MessageModel
 * @package Module\OnlineService\Domain\Models
 * @property integer $id
 * @property integer $user_id
 * @property integer $session_id
 * @property integer $receive_id
 * @property integer $type
 * @property string $content
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class MessageModel extends Model {
    public static function tableName() {
        return 'service_message';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'session_id' => 'required|int',
            'receive_id' => 'int',
            'type' => 'int:0,127',
            'content' => 'string:0,255',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'session_id' => 'Session Id',
            'receive_id' => 'Receive Id',
            'type' => 'Type',
            'content' => 'Content',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}