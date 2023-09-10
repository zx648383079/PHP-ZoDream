<?php
namespace Module\Legwork\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class CategoryEntity
 * @package Module\Legwork\Domain\Entities
 * @property integer $id
 * @property string $name
 * @property string $icon
 * @property string $description
 */
class CategoryEntity extends Entity {
    public static function tableName(): string {
        return 'leg_category';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,100',
            'icon' => 'string:0,200',
            'description' => 'string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'icon' => 'Icon',
            'description' => 'Description',
        ];
    }
}