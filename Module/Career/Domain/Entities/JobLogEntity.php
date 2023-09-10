<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 工作申请记录
 * @package Module\Career\Domain\Entities
 */
class JobLogEntity extends Entity {
    public static function tableName(): string {
        return 'career_job_log';
    }
}