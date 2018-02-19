<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * Class GoodsImageModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $goods_id
 * @property string $file
 * @property integer $size
 */
class GoodsImageModel extends Model {
    public static function tableName() {
        return 'goods_image';
    }
}