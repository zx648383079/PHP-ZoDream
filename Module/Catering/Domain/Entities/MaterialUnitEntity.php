<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 原材料单位换算
 * @package Module\Catering\Domain\Entities
 */
class MaterialUnitEntity extends Entity {
    public static function tableName() {
        return 'eat_material_unit';
    }
}