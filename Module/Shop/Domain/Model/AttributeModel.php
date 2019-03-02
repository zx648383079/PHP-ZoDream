<?php
namespace Module\Shop\Domain\Model;


use Domain\Model\Model;
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
class AttributeModel extends Model {

    public static $search_list = ['不需要检索', '关键字检索', '范围检索'];
    public static $type_list = ['唯一属性', '单选属性', '复选属性'];

    public static function tableName() {
        return 'shop_attribute';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
            'group_id' => 'required|int',
            'type' => 'int:0,9',
            'search_type' => 'int:0,9',
            'input_type' => 'int:0,9',
            'default_value' => 'string:0,255',
            'position' => 'int:0,999',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => '名称',
            'group_id' => '分组',
            'type' => '类型',
            'search_type' => '搜索类型',
            'input_type' => '输入类型',
            'default_value' => '默认值',
            'position' => '排序',
        ];
    }

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