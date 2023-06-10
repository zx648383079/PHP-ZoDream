<?php
namespace Module\Shop\Domain\Models;


use Domain\Model\Model;
use Module\Shop\Domain\Entities\ProductEntity;

/**
 * Class ProductModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property integer $goods_id
 * @property float $price
 * @property float $market_price
 * @property integer $stock
 * @property string $series_number
 * @property string $attributes
 * @property string $attributes_value
 * @property float $weight
 */
class ProductModel extends ProductEntity {

    const ATTRIBUTE_LINK = ',';


    public function getAttributesValue() {
        $ids = explode(self::ATTRIBUTE_LINK, $this->attributes);
        $data = AttributeModel::whereIn('id', $ids)->where('goods_id', $this->goods_id)->pluck('value');
        return implode(',', $data);
    }

    public static function batchSave($data, $goods_id) {
        foreach ($data as $item) {
            $model = static::where('attributes', $item['attributes'])->first();
            if (empty($model)) {
                $model = new static();
            }
            if (self::isEmptyForm($item['form'])) {
                !$model->isNewRecord && $model->delete();
                continue;
            }
            $model->attributes = $item['attributes'];
            $model->goods_id = $goods_id;
            if (!$model->load($item['form'])) {
                continue;
            }
            $model->price = floatval($model->price);
            $model->market_price = floatval($model->market_price);
            $model->stock = intval($model->stock);
            $model->save();
        }
        return true;
    }

    private static function isEmptyForm($data) {
        if (empty($data)) {
            return true;
        }
        $maps = ['price', 'market_price', 'stock', 'series_number'];
        foreach ($maps as $key) {
            if (isset($data[$key]) && $data[$key] !== '') {
                return false;
            }
        }
        return true;
    }

}