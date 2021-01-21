<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 食谱，库存组成商品的配方
 * @package Module\Catering\Domain\Entities
 */
class RecipeEntity extends Entity {
    public static function tableName() {
        return 'eat_recipe';
    }
}