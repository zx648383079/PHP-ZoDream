<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 求职者基本信息
 * @package Module\Career\Domain\Entities
 */
class ProfileEntity extends Entity {
    public static function tableName(): string {
        return 'career_profile';
    }
}