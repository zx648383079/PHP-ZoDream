<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 商品
 * @property integer $id
 * @property integer $store_id
 * @property integer $cat_id
 * @property string $name
 * @property string $image
 * @property integer $recipe_id
 * @property string $description
 * @property integer $updated_at
 * @property integer $created_at
 */
class GoodsEntity extends Entity {
    public static function tableName() {
        return 'eat_goods';
    }

    protected function rules() {
        return [
            'store_id' => 'required|int',
            'cat_id' => 'required|int',
            'name' => 'required|string:0,255',
            'image' => 'string:0,255',
            'recipe_id' => 'int',
            'description' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'store_id' => 'Store Id',
            'cat_id' => 'Cat Id',
            'name' => 'Name',
            'image' => 'Image',
            'recipe_id' => 'Recipe Id',
            'description' => 'Description',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}