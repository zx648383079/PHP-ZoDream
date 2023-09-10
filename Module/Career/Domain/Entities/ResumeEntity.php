<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 求职期望/简历
 * @package Module\Career\Domain\Entities
 */
class ResumeEntity extends Entity {
    public static function tableName(): string {
        return 'career_resume';
    }
}