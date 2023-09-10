<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $image
 * @property string $description
 * @property integer $hp
 * @property integer $mp
 * @property integer $att
 * @property integer $def
 * @property integer $status
 */
class CharacterIdentityEntity extends Entity {
    public static function tableName(): string {
        return 'gm_character_identity';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'name' => 'required|string:0,255',
            'image' => 'string:0,255',
            'description' => 'string:0,255',
            'hp' => 'int',
            'mp' => 'int',
            'att' => 'int',
            'def' => 'int',
            'status' => 'int:0,127',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'name' => 'Name',
            'image' => 'Image',
            'description' => 'Description',
            'hp' => 'Hp',
            'mp' => 'Mp',
            'att' => 'Att',
            'def' => 'Def',
            'status' => 'Status',
        ];
    }
}