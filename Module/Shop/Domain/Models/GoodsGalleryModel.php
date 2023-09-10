<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

/**
 * Class GoodsImageModel
 * @property integer $id
 * @property integer $goods_id
 * @property string $thumb
 * @property string $file
 * @property integer $type
 */
class GoodsGalleryModel extends Model {
    public static function tableName(): string {
        return 'shop_goods_gallery';
    }

    public function rules(): array {
        return [
            'goods_id' => 'required|int',
            'type' => 'int:0,100',
            'thumb' => 'required|string:0,255',
            'file' => 'required|string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'goods_id' => 'Goods Id',
            'image' => 'Image',
        ];
    }

    public function getThumbAttribute() {
        $thumb = $this->getAttributeSource('thumb');
        if (empty($thumb)) {
            return '';
        }
        return url()->asset($thumb);
    }

    public function getFileAttribute() {
        $thumb = $this->getAttributeSource('file');
        if (empty($thumb)) {
            return '';
        }
        return url()->asset($thumb);
    }

    public static function batchSave($data, $id) {
        if (empty($data)) {
            return;
        }
        $exist = static::where('goods_id', $id)->where('type', 0)->pluck('file');
        $diff = empty($exist) ? $data : array_diff($data, $exist);
        if (!empty($diff)) {
            $diff = array_map(function ($item) use ($id) {
                return [
                    'goods_id' => $id,
                    'thumb' => $item,
                    'file' => $item
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
        static::where('goods_id', $id)->where('type', 0)->whereIn('file', $del)->delete();
    }

}