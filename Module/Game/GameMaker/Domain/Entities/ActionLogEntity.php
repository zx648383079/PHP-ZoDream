<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $item_type
 * @property integer $item_id
 * @property integer $action
 * @property string $remark
 * @property integer $created_at
 */
class ActionLogEntity extends Entity {
    public static function tableName(): string {
        return 'gm_action_log';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'user_id' => 'required|int',
            'item_type' => 'int:0,127',
            'item_id' => 'required|int',
            'action' => 'required|int',
            'remark' => 'string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'user_id' => 'User Id',
            'item_type' => 'Item Type',
            'item_id' => 'Item Id',
            'action' => 'Action',
            'remark' => 'Remark',
            'created_at' => 'Created At',
        ];
    }


}