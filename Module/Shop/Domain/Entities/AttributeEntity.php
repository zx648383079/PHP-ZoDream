<?php
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;

/**
 * Class AttributeEntity
 * @package Module\Shop\Domain\Entities
 * @property integer $id
 * @property string $name
 * @property integer $group_id
 * @property integer $type
 * @property integer $search_type
 * @property integer $input_type
 * @property string $default_value
 * @property integer $position
 * @property string $property_group
 */
class AttributeEntity extends Entity {

    public static function tableName(): string {
        return 'shop_attribute';
    }
    protected function rules(): array {
        return [
            'name' => 'required|string:0,30',
            'group_id' => 'required|int',
            'type' => 'int:0,127',
            'search_type' => 'int:0,127',
            'input_type' => 'int:0,127',
            'default_value' => 'string:0,255',
            'position' => 'int',
            'property_group' => 'string:0,20',
        ];
    }


    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '名称',
            'group_id' => '分组',
            'type' => '类型',
            'search_type' => '搜索类型',
            'input_type' => '输入类型',
            'default_value' => '默认值',
            'position' => '排序',
            'property_group' => '属性分组',
        ];
    }
}