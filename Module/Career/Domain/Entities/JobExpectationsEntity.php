<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 求职期望
 * @package Module\Career\Domain\Entities
 */
class JobExpectationsEntity extends Entity {
    public static function tableName() {
        return 'career_job_expectations';
    }
}