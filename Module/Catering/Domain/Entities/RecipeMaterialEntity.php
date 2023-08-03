<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 食谱，库存组成商品的配方
 * @property integer $id
 * @property integer $recipe_id
 * @property integer $material_id
 * @property float $amount
 * @property integer $unit
 */
class RecipeMaterialEntity extends Entity {
    public static function tableName() {
        return 'eat_recipe_material';
    }

    protected function rules() {
        return [
            'recipe_id' => 'required|int',
            'material_id' => 'required|int',
            'amount' => 'required|string',
            'unit' => 'required|int:0,127',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'recipe_id' => 'Recipe Id',
            'material_id' => 'Material Id',
            'amount' => 'Amount',
            'unit' => 'Unit',
        ];
    }
}