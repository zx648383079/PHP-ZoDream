<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 原材料
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property string $description
 */
class MaterialEntity extends Entity {
    public static function tableName(): string {
        return 'eat_material';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,255',
            'image' => 'string:0,255',
            'description' => 'string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'image' => 'Image',
            'description' => 'Description',
        ];
    }
}