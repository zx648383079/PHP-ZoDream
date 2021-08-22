<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 原材料参考价格
 * @package Module\Catering\Domain\Entities
 */
class MaterialPriceEntity extends Entity {
    public static function tableName() {
        return 'eat_material_price';
    }
}