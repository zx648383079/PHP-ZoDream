<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

class AddressEntity extends Entity {
    public static function tableName() {
        return 'eat_address';
    }
}