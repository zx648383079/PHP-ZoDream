<?php
namespace Module\Shop\Domain\Models;


use Domain\Model\Model;
use Module\Shop\Domain\Entities\GoodsAttributeEntity;

/**
 * Class GoodsAttributeModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property integer $goods_id
 * @property integer $attribute_id
 * @property string $value
 * @property float $price
 */
class GoodsAttributeModel extends GoodsAttributeEntity {


    public function checkValue() {
        $count = static::where('goods_id', $this->goods_id)->where('attribute_id', $this->attribute_id)->where('value', $this->value)
            ->where('id', '<>', $this->id)->count();
        return $count < 1;
    }

    /**
     * 批量插入属性
     * @param array $attr
     * @param $goods_id
     * @return array
     * @throws \Exception
     */
    public static function batchSave(array $attr, $goods_id) {
        $models = [];
        foreach ($attr as $attr_id => $data) {
            $attributeModel = AttributeModel::find($attr_id);
            if (empty($attributeModel)) {
                continue;
            }
            $i = -1;
            foreach ($data as $id => $item) {
                $i ++;
                $attrModel = $i == $id ? new static([
                    'goods_id' => $goods_id,
                    'attribute_id' => $attributeModel->id,
                ]) : static::find($id);
                if (empty($attrModel)) {
                    continue;
                }
                if (isset($item['value'])) {
                    $attrModel->value = $item['value'];
                    if (!$attrModel->checkValue()) {
                        continue;
                    }
                }
                if (isset($item['price'])) {
                    $attrModel->price = floatval($item['price']);
                }
                if (!$attrModel->save()) {
                    continue;
                }
                $models[] = $attrModel;
            }
        }
        return $models;
    }
}