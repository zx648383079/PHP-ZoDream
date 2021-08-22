<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 奖项
 * @package Module\Career\Domain\Entities
 */
class AwardEntity extends Entity {
    public static function tableName() {
        return 'career_award';
    }
}