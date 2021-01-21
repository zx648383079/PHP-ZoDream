<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 商品
 * @package Module\Catering\Domain\Entities
 */
class GoodsEntity extends Entity {
    public static function tableName() {
        return 'eat_goods';
    }
}