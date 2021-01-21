<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 店铺
 * @package Module\Catering\Domain\Entities
 */
class StoreEntity extends Entity {
    public static function tableName() {
        return 'eat_store';
    }
}