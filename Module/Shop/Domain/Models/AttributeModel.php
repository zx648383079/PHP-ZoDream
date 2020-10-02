<?php
namespace Module\Shop\Domain\Models;

use Module\Shop\Domain\Entities\AttributeEntity;
use Zodream\Helpers\Json;

/**
 * Class AttributeModel
 * @package Module\Shop\Domain\Model
 * @property integer $id
 * @property string $name
 * @property integer $group_id
 * @property integer $type
 * @property integer $search_type
 * @property integer $input_type
 * @property string $default_value
 * @property integer $position
 */
class AttributeModel extends AttributeEntity {

    public static $search_list = ['不需要检索', '关键字检索', '范围检索'];
    public static $type_list = ['唯一属性', '单选属性', '复选属性'];

    protected $append = ['group'];

    public function group() {
        return $this->hasOne(AttributeGroupModel::class, 'id', 'group_id');
    }

    /**
     * @param $properties
     * @param $goods_id
     * @return array
     */
    public static function getProductAndPriceWithProperties($properties, $goods_id) {
        static $cache = [];
        $key = md5(is_array($properties) ? Json::encode($properties) : $properties .$goods_id);
        if (isset($cache[$key])) {
            return $cache[$key];
        }
        list($product_properties, $properties) = AttributeModel::splitProperties($properties);
        $properties_price = empty($properties) ? 0 :
            GoodsAttributeModel::whereIn('id', $properties)->sum('price');
        $product = empty($product_properties) ? null :
            ProductModel::findByAttribute($product_properties, $goods_id);
        return $cache[$key] = compact('product_properties', 'properties', 'properties_price', 'product');
    }

    /**
     * 分离商品规格和附加属性
     * @param $properties
     * @return array [product, properties]
     */
    public static function splitProperties($properties) {
        if (empty($properties)) {
            return [[], []];
        }
        if (!is_array($properties)) {
            $properties = Json::decode($properties);
        }
        $data = [];
        foreach ($properties as $item) {
            $args = explode(':', $item);
            $id = 0;
            if (count($args) > 1) {
                $id = intval($args[0]);
                $value = intval($args[1]);
            } else {
                $value = intval($args[0]);
            }
            if ($value < 1) {
                continue;
            }
            if ($id < 1) {
                $id = GoodsAttributeModel::where('id', $value)->value('attribute_id');
            }
            if (!isset($data[$id])) {
                $data[$id] = [$value];
                continue;
            }
            if (in_array($value, $data[$id])) {
                continue;
            }
            $data[$id][] = $value;
        }
        if (empty($data)) {
            return [[], []];
        }
        $attr_list = static::whereIn('id', array_keys($data))->get('id', 'type');
        $properties = $product_properties = [];
        foreach ($attr_list as $item) {
            if ($item->type == 2) {
                $properties = array_merge($properties, $data[$item->id]);
                continue;
            }
            $product_properties = array_merge($product_properties, $data[$item->id]);
        }
        return [$product_properties, $properties];
    }

}