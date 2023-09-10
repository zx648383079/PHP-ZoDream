<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 求职城市
 * @package Module\Career\Domain\Entities
 */
class ResumeRegionEntity extends Entity {
    public static function tableName(): string {
        return 'career_resume_region';
    }
}