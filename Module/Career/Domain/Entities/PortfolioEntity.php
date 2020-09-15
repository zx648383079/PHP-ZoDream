<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 项目作品
 * @package Module\Career\Domain\Entities
 */
class PortfolioEntity extends Entity {
    public static function tableName() {
        return 'career_portfolio';
    }
}