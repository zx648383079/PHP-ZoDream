<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 商品分类
 * @package Module\Catering\Domain\Entities
 */
class CategoryEntity extends Entity {
    public static function tableName() {
        return 'eat_category';
    }
}