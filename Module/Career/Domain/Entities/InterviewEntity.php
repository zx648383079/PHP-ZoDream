<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 面试
 * @package Module\Career\Domain\Entities
 */
class InterviewEntity extends Entity {
    public static function tableName() {
        return 'career_interview';
    }
}