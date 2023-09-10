<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 工作经历
 * @package Module\Career\Domain\Entities
 */
class WorkExperienceEntity extends Entity {
    public static function tableName(): string {
        return 'career_work_experience';
    }
}