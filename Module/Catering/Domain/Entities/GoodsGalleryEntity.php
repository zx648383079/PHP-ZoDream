<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 商品预览图
 * @package Module\Catering\Domain\Entities
 */
class GoodsGalleryEntity extends Entity {
    public static function tableName() {
        return 'eat_goods_gallery';
    }
}