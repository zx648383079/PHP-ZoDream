<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 原材料
 * @package Module\Catering\Domain\Entities
 */
class MaterialEntity extends Entity {
    public static function tableName() {
        return 'eat_material';
    }
}