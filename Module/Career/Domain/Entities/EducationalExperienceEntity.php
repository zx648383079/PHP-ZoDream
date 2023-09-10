<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 教育经历
 * @package Module\Career\Domain\Entities
 */
class EducationalExperienceEntity extends Entity {
    public static function tableName(): string {
        return 'career_educational_experience';
    }
}