<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺员工
 * @package Module\Catering\Domain\Entities
 */
class StoreStaffEntity extends Entity {
    public static function tableName() {
        return 'eat_store_staff';
    }
}