<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 社区影响力
 * @package Module\Career\Domain\Entities
 */
class InfluenceEntity extends Entity {
    public static function tableName() {
        return 'career_influence';
    }
}