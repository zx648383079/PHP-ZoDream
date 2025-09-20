<?php
declare(strict_types = 1);
namespace Module\Auth\Domain\Entities;

use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property string $name
 * @property string $icon
 * @property string $description
 * @property integer $is_open
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class ZoneEntity extends Entity {

    public static function tableName(): string
    {
        return 'user_zone';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,30',
            'icon' => 'string:0,255',
            'description' => 'string:0,255',
            'is_open' => 'int:0,127',
            'status' => 'int:0,127',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'icon' => 'Icon',
            'description' => 'Description',
            'is_open' => 'Is Open',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
