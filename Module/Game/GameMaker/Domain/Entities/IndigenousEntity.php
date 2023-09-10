<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $description
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $hp
 * @property integer $mp
 * @property integer $att
 * @property integer $def
 * @property integer $crt
 * @property integer $lck
 * @property integer $dex
 * @property integer $chr
 * @property integer $int
 */
class IndigenousEntity extends Entity {
    public static function tableName(): string {
        return 'gm_indigenous';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'name' => 'required|string:0,255',
            'description' => 'required|string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
            'hp' => 'int',
            'mp' => 'int',
            'att' => 'int',
            'def' => 'int',
            'crt' => 'int',
            'lck' => 'int',
            'dex' => 'int',
            'chr' => 'int',
            'int' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'name' => 'Name',
            'description' => 'Description',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'hp' => 'Hp',
            'mp' => 'Mp',
            'att' => 'Att',
            'def' => 'Def',
            'crt' => 'Crt',
            'lck' => 'Lck',
            'dex' => 'Dex',
            'chr' => 'Chr',
            'int' => 'Int',
        ];
    }
}