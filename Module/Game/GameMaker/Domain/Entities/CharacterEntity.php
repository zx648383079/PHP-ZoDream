<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $project_id
 * @property string $nickname
 * @property integer $sex
 * @property integer $grade
 * @property string $money
 * @property string $exp
 * @property integer $x
 * @property integer $y
 * @property integer $updated_at
 * @property integer $created_at
 */
class CharacterEntity extends Entity {
    public static function tableName() {
        return 'gm_character';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'project_id' => 'required|int',
            'nickname' => 'required|string:0,255',
            'sex' => 'int:0,127',
            'grade' => 'int',
            'money' => 'string:0,20',
            'exp' => 'string:0,20',
            'x' => 'int',
            'y' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'project_id' => 'Project Id',
            'nickname' => 'Nickname',
            'sex' => 'Sex',
            'grade' => 'Grade',
            'money' => 'Money',
            'exp' => 'Exp',
            'x' => 'X',
            'y' => 'Y',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}