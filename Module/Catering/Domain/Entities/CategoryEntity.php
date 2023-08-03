<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 商品分类
 * @property integer $id
 * @property integer $store_id
 * @property integer $type
 * @property string $name
 * @property integer $parent_id
 */
class CategoryEntity extends Entity {
    public static function tableName() {
        return 'eat_category';
    }


    protected function rules() {
        return [
            'store_id' => 'required|int',
            'type' => 'int:0,127',
            'name' => 'required|string:0,255',
            'parent_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'type' => 'Type',
            'name' => 'Name',
            'parent_id' => 'Parent Id',
        ];
    }
}