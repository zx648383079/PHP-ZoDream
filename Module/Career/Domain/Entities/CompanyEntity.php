<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 公司
 * @package Module\Career\Domain\Entities
 */
class CompanyEntity extends Entity {
    public static function tableName(): string {
        return 'career_company';
    }
}