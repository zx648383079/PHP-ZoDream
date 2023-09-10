<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $type
 * @property string $name
 * @property string $description
 * @property integer $sub_type
 * @property string $icon
 * @property string $effect
 */
class ItemEntity extends Entity {
    public static function tableName(): string {
        return 'gm_item';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'type' => 'required|int',
            'name' => 'required|string:0,255',
            'description' => 'string:0,255',
            'sub_type' => 'int',
            'icon' => 'required|string:0,255',
            'effect' => 'string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'type' => 'Type',
            'name' => 'Name',
            'description' => 'Description',
            'sub_type' => 'Sub Type',
            'icon' => 'Icon',
            'effect' => 'Effect',
        ];
    }

}