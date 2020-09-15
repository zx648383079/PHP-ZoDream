<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

class StoreEntity extends Entity {
    public static function tableName() {
        return 'eat_store';
    }
}