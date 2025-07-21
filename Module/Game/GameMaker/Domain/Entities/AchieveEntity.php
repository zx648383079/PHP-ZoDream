<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Entities;


use Domain\Entities\Entity;

/**
 * 成就
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $description
 * @property string $icon
 * @property integer $demand
 */
class AchieveEntity extends Entity {
    public static function tableName(): string {
        return 'gm_achieve';
    }

    protected function rules(): array {
        return [
            'project_id' => 'required|int',
            'name' => 'required|string:0,255',
            'description' => 'string:0,255',
            'icon' => 'required|string:0,255',
            'demand' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'project_id' => 'Project Id',
            'name' => '名称',
            'description' => '说明',
            'icon' => '图标',
            'demand' => '达成需求',
        ];
    }
}