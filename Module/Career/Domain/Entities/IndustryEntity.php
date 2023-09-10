<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 行业
 * @package Module\Career\Domain\Entities
 */
class IndustryEntity extends Entity {
    public static function tableName(): string {
        return 'career_industry';
    }
}