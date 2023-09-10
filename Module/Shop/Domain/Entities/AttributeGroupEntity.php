<?php
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;

/**
 * @property integer $id
 * @property string $name
 * @property integer $updated_at
 * @property integer $created_at
 * @property string $property_groups
 */
class AttributeGroupEntity extends Entity {

    public static function tableName(): string {
        return 'shop_attribute_group';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,30',
            'updated_at' => 'int',
            'created_at' => 'int',
            'property_groups' => 'string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'property_groups' => 'Property Groups',
        ];
    }

}