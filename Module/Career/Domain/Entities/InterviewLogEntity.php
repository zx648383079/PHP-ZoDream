<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 面试记录
 * @package Module\Career\Domain\Entities
 */
class InterviewLogEntity extends Entity {
    public static function tableName(): string {
        return 'career_interview_log';
    }
}