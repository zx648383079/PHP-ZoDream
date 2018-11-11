<?php
namespace Module\Shop\Domain\Model;


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
 */
class ProductModel extends Model {

    public static function tableName() {
        return 'shop_product';
    }

    protected function rules() {
        return [
            'goods_id' => 'required|int',
            'price' => '',
            'market_price' => '',
            'stock' => 'int',
            'series_number' => 'string:0,50',
            'attributes' => 'string:0,100',
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
        $ids = explode('_', $this->attributes);
        $data = AttributeModel::whereIn('id', $ids)->where('goods_id', $this->goods_id)->pluck('value');
        return implode(',', $data);
    }

    public static function findByAttribute(array $data, $goods_id) {
        sort($data);
        $attributes = implode('_', $data);
        return static::where('attributes', $attributes)->where('goods_id', $goods_id)->first();
    }

    public static function batchSave($data, $goods_id) {
        foreach ($data as $item) {
            $model = static::where('attributes', $item['attributes'])->first();
            if (empty($model)) {
                $model = new static();
            }
            $model->goods_id = $goods_id;
            if (empty($item['form'])) {
                !$model->isNewRecord && $model->delete();
                continue;
            }
            if ($model->load($item['form'])) {
                continue;
            }
            $model->save();
        }
        return true;
    }

}