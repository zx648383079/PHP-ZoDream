<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 食谱，库存组成商品的配方
 * @property integer $id
 * @property integer $cat_id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $name
 * @property string $image
 * @property string $description
 * @property string $remark
 * @property integer $updated_at
 * @property integer $created_at
 */
class RecipeEntity extends Entity {
    public static function tableName() {
        return 'eat_recipe';
    }

    protected function rules() {
        return [
            'cat_id' => 'required|int',
            'store_id' => 'int',
            'user_id' => 'required|int',
            'name' => 'required|string:0,255',
            'image' => 'string:0,255',
            'description' => 'string:0,255',
            'remark' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'cat_id' => 'Cat Id',
            'store_id' => 'Store Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'image' => 'Image',
            'description' => 'Description',
            'remark' => 'Remark',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}