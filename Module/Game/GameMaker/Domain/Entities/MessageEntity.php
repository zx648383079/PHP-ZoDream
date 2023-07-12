<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $receive_id
 * @property integer $item_type
 * @property integer $item_id
 * @property string $content
 * @property string $extra_rule
 * @property integer $created_at
 */
class MessageEntity extends Entity {
    public static function tableName() {
        return 'gm_message';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'user_id' => 'required|int',
            'receive_id' => 'required|int',
            'item_type' => 'int:0,127',
            'item_id' => 'int',
            'content' => 'required|string:0,400',
            'extra_rule' => 'string:0,400',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'user_id' => 'User Id',
            'receive_id' => 'Receive Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'content' => 'Content',
            'extra_rule' => 'Extra Rule',
            'created_at' => 'Created At',
        ];
    }
}