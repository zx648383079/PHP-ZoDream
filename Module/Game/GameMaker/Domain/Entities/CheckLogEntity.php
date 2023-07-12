<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;

use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $type
 * @property integer $running
 * @property string $reward
 * @property integer $created_at
 */
class CheckLogEntity extends Entity {
    public static function tableName() {
        return 'gm_check_log';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'user_id' => 'required|int',
            'type' => 'int:0,127',
            'running' => 'int',
            'reward' => 'string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'user_id' => 'User Id',
            'type' => 'Type',
            'running' => 'Running',
            'reward' => 'Reward',
            'created_at' => 'Created At',
        ];
    }
}