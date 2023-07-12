<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property string $name
 * @property integer $updated_at
 * @property integer $created_at
 */
class HouseEntity extends Entity {
    public static function tableName() {
        return 'gm_house';
    }

    protected function rules() {
        return [
            'project_id' => 'required|int',
            'user_id' => 'int',
            'name' => 'required|string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}