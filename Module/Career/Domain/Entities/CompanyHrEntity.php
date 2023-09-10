<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 公司hr
 * @package Module\Career\Domain\Entities
 */
class CompanyHrEntity extends Entity {
    public static function tableName(): string {
        return 'career_company_hr';
    }
}