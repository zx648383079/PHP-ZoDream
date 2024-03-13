<?php
namespace Module\Bot\Domain\Model;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property string $name
 * @property integer $bot_id
 * @property string $tag_id
 */
class UserGroupModel extends Model {
    /**
     * @inheritdoc
     */
    public static function tableName(): string {
        return 'bot_user_group';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,20',
            'bot_id' => 'required|int',
            'tag_id' => 'string:0,20',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'bot_id' => 'bot_id',
            'tag_id' => 'Tag Id',
        ];
    }

}