<?php
namespace Module\Shop\Domain\Models;

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

    public function getImageAttribute() {
        $thumb = $this->getAttributeSource('image');
        if (empty($thumb)) {
            return '';
        }
        return url()->asset($thumb);
    }

    public static function batchSave($data, $id) {
        if (empty($data)) {
            return;
        }
        $exist = static::where('goods_id', $id)->pluck('image');
        $diff = empty($exist) ? $data : array_diff($data, $exist);
        if (!empty($diff)) {
            $diff = array_map(function ($item) use ($id) {
                return [
                    'goods_id' => $id,
                    'image' => $item,
                ];
            }, $diff);
            static::query()->insert($diff);
        }
        if (empty($exist)) {
            return;
        }
        $del = array_diff($exist, $data);
        if (empty($del)) {
            return;
        }
        static::where('goods_id', $id)->whereIn('image', $del)->delete();
    }

}