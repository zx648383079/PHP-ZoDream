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
 * @property integer $hp
 * @property integer $mp
 * @property integer $att
 * @property integer $def
 * @property integer $identity_id
 * @property integer $crt
 * @property integer $lck
 * @property integer $dex
 * @property integer $chr
 * @property integer $int
 * @property integer $org_id
 * @property integer $team_id
 * @property integer $descent_id
 */
class CharacterEntity extends Entity {
    public static function tableName(): string {
        return 'gm_character';
    }

    protected function rules(): array {
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
            'hp' => 'int',
            'mp' => 'int',
            'att' => 'int',
            'def' => 'int',
            'identity_id' => 'int',
            'crt' => 'int',
            'lck' => 'int',
            'dex' => 'int',
            'chr' => 'int',
            'int' => 'int',
            'org_id' => 'int',
            'team_id' => 'int',
            'descent_id' => 'int',
        ];
    }

    protected function labels(): array {
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
            'hp' => 'Hp',
            'mp' => 'Mp',
            'att' => 'Att',
            'def' => 'Def',
            'identity_id' => 'Identity Id',
            'crt' => 'Crt',
            'lck' => 'Lck',
            'dex' => 'Dex',
            'chr' => 'Chr',
            'int' => 'Int',
            'org_id' => 'Org Id',
            'team_id' => 'Team Id',
            'descent_id' => 'Descent Id',
        ];
    }
}