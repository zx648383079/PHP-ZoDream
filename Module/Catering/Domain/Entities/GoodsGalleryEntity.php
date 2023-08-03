<?php
namespace Module\Catering\Domain\Entities;

use Domain\Entities\Entity;

/**
 * 商品预览图
 * @property integer $id
 * @property integer $goods_id
 * @property string $thumb
 * @property integer $file_type
 * @property string $file
 */
class GoodsGalleryEntity extends Entity {
    public static function tableName() {
        return 'eat_goods_gallery';
    }

    protected function rules() {
        return [
            'goods_id' => 'required|int',
            'thumb' => 'string:0,255',
            'file_type' => 'int:0,127',
            'file' => 'required|string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'goods_id' => 'Goods Id',
            'thumb' => 'Thumb',
            'file_type' => 'File Type',
            'file' => 'File',
        ];
    }
}