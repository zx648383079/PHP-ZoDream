<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 工作
 * @package Module\Career\Domain\Entities
 */
class JobEntity extends Entity {
    public static function tableName() {
        return 'career_job';
    }
}