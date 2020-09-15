<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 个人技能
 * @package Module\Career\Domain\Entities
 */
class SkillEntity extends Entity {
    public static function tableName() {
        return 'career_skill';
    }
}