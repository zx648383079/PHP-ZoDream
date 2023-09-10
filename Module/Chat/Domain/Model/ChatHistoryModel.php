<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;


/**
 * @property integer $id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $user_id
 * @property integer $unread_count
 * @property integer $last_message
 * @property integer $updated_at
 * @property integer $created_at
 */
class ChatHistoryModel extends Model {
    public static function tableName(): string {
        return 'chat_history';
    }

    protected function rules(): array {
        return [
            'item_type' => 'required|int:0,127',
            'item_id' => 'required|int',
            'user_id' => 'required|int',
            'unread_count' => 'required|int',
            'last_message' => 'required|int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'user_id' => 'User Id',
            'unread_count' => 'Unread Count',
            'last_message' => 'Last Message',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}