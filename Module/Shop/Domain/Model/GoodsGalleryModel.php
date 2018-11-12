<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * Class GoodsImageModel
 * @property integer $id
 * @property integer $goods_id
 * @property string $image
 */
class GoodsGalleryModel extends Model {
    public static function tableName() {
        return 'shop_goods_gallery';
    }

    protected function rules() {
        return [
            'goods_id' => 'required|int',
            'image' => 'required|string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'goods_id' => 'Goods Id',
            'image' => 'Image',
        ];
    }

}