<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * 血统
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property integer $hp
 * @property integer $mp
 * @property integer $att
 * @property integer $def
 * @property integer $crt
 * @property integer $lck
 * @property integer $dex
 * @property integer $chr
 * @property integer $int
 * @property string $specialty
 */
class DescentEntity extends Entity {
    public static function tableName(): string {
        return 'gm_descent';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'name' => 'required|string:0,255',
            'hp' => 'int',
            'mp' => 'int',
            'att' => 'int',
            'def' => 'int',
            'crt' => 'int',
            'lck' => 'int',
            'dex' => 'int',
            'chr' => 'int',
            'int' => 'int',
            'specialty' => 'string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'name' => 'Name',
            'hp' => 'Hp',
            'mp' => 'Mp',
            'att' => 'Att',
            'def' => 'Def',
            'crt' => 'Crt',
            'lck' => 'Lck',
            'dex' => 'Dex',
            'chr' => 'Chr',
            'int' => 'Int',
            'specialty' => 'Specialty',
        ];
    }

}