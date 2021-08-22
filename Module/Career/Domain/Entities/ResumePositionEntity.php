<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 求职岗位
 * @package Module\Career\Domain\Entities
 */
class ResumePositionEntity extends Entity {
    public static function tableName() {
        return 'career_resume_position';
    }
}