<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 用户收货地址
 * @package Module\Catering\Domain\Entities
 */
class AddressEntity extends Entity {
    public static function tableName() {
        return 'eat_address';
    }
}