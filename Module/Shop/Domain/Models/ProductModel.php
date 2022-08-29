<?php
namespace Module\Shop\Domain\Models;


use Domain\Model\Model;

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
class ProductModel extends Model {

    const ATTRIBUTE_LINK = ',';

    public static function tableName() {
        return 'shop_product';
    }

    public function rules() {
        return [
            'goods_id' => 'required|int',
            'price' => '',
            'market_price' => '',
            'stock' => 'int',
            'series_number' => 'string:0,50',
            'attributes' => 'string:0,100',
            'weight' => 'numeric'
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'goods_id' => 'Goods Id',
            'price' => 'Price',
            'market_price' => '市场价',
            'stock' => 'Stock',
            'series_number' => 'Series Number',
            'attributes' => 'Attributes',
        ];
    }

    public function getAttributesValue() {
        $ids = explode(self::ATTRIBUTE_LINK, $this->attributes);
        $data = AttributeModel::whereIn('id', $ids)->where('goods_id', $this->goods_id)->pluck('value');
        return implode(',', $data);
    }

    /**
     * @param array $data
     * @param $goods_id
     * @return static
     */
    public static function findByAttribute(array $data, $goods_id) {
        sort($data);
        $attributes = implode(self::ATTRIBUTE_LINK, $data);
        return static::where('attributes', $attributes)->where('goods_id', $goods_id)->first();
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