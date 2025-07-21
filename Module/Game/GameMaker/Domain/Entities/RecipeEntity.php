<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 配方
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $icon
 * @property string $description
 * @property string $data
 */
class RecipeEntity extends Entity {
    public static function tableName(): string {
        return 'gm_recipe';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'name' => 'required|string:0,255',
            'icon' => 'string:0,255',
            'description' => 'string:0,255',
            'data' => 'string',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'name' => 'Name',
            'icon' => 'Icon',
            'description' => 'Description',
            'data' => 'Data',
        ];
    }

}