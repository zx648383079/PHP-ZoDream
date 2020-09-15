<?php
namespace Module\Career\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 公司
 * @package Module\Career\Domain\Entities
 */
class CompanyHrEntity extends Entity {
    public static function tableName() {
        return 'career_company_hr';
    }
}