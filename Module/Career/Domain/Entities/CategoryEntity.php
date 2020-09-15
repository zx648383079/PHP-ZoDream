<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 职业
 * @package Module\Career\Domain\Entities
 */
class CategoryEntity extends Entity {
    public static function tableName() {
        return 'career_category';
    }
}