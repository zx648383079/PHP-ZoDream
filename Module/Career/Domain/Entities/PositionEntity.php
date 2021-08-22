<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 职位
 * @package Module\Career\Domain\Entities
 */
class PositionEntity extends Entity {
    public static function tableName() {
        return 'career_position';
    }
}