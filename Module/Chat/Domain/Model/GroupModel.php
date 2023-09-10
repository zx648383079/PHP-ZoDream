<?php
namespace Module\Chat\Domain\Model;

use Domain\Model\Model;

/**
 * Class GroupModel
 * @package Module\Chat\Domain\Model
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property string $description
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class GroupModel extends Model {
    public static function tableName(): string {
        return 'chat_group';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'logo' => 'required|string:0,100',
            'description' => 'string:0,100',
            'user_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'logo' => 'Logo',
            'description' => 'Description',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}