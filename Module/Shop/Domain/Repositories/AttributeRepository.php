<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;


use Module\Shop\Domain\Entities\AttributeEntity;
use Module\Shop\Domain\Entities\GoodsAttributeEntity;
use Module\Shop\Domain\Models\AttributeModel;
use Module\Shop\Domain\Models\AttributeUniqueModel;
use Module\Shop\Domain\Models\GoodsAttributeModel;
use Module\Shop\Domain\Models\ProductModel;
use Zodream\Database\Relation;
use Zodream\Helpers\Json;

final class AttributeRepository {


    /**
     * 根据属性选择值获取货品和附加属性
     * @param int[] $properties
     * @param int $goods
     * @return array{product_properties: array, properties: array, properties_price: float, properties_label: string, product: ProductModel|null}
     */
    public static function getProductAndPriceWithProperties(array $properties, int $goods): array
    {
        static $cache = [];
        $key = md5(Json::encode($properties) .$goods);
        if (isset($cache[$key])) {
            return $cache[$key];
        }
        list($product_properties, $properties, $properties_price, $properties_label) = self::splitProperties($properties);
        $product = empty($product_properties) ? null :
            self::getProduct($product_properties, $goods);
        return $cache[$key] = compact('product_properties', 'properties', 'properties_price', 'product', 'properties_label');
    }

    /**
     * 根据属性值获取货品
     * @param array $properties
     * @param int $goods
     * @return ProductModel|null
     */
    private static function getProduct(array $properties, int $goods) {
        sort($properties);
        $attributes = implode(ProductModel::ATTRIBUTE_LINK, $properties);
        return ProductModel::where('attributes', $attributes)->where('goods_id', $goods)->first();
    }

    public static function formatPostProperties($properties): array {
        $data = [];
        if (empty($properties)) {
            return $data;
        }
        if (!is_array($properties)) {
            $properties = Json::decode($properties);
        }
        foreach ((array)$properties as $item) {
            $args = explode(':', $item);
            $value = intval(count($args) > 1 ? $args[1] : $args[0]);
            if ($value < 1 || in_array($value, $data)) {
                continue;
            }
            $data[] = $value;
        }
        sort($data);
        return $data;
    }

    /**
     * 分离商品规格和附加属性
     * @param int[] $properties
     * @return array [product: [], properties: [], properties_price: 0, properties_label: '']
     */
    public static function splitProperties(array $properties) {
        if (empty($properties)) {
            return [[], [], 0, ''];
        }
        $items = GoodsAttributeEntity::whereIn('id', $properties)->get('attribute_id', 'id', 'price', 'value');
        if (empty($data)) {
            return [[], [], 0, ''];
        }

        $attrId = [];
        $data = [];
        foreach ($items as $item) {
            $attrId[] = $item['attribute_id'];
            if (!isset($data[$item['attribute_id']])) {
                $data[$item['attribute_id']] = [
                    'price' => 0,
                    'items' => [],
                    'label' => []
                ];
            }
            $data[$item['attribute_id']]['price'] += $item['price'];
            $data[$item['attribute_id']]['items'][] = $item['id'];
            $data[$item['attribute_id']]['label'][] = $item['value'];
        }
        $properties_price = 0;
        $properties_label = [];
        $attr_list = AttributeEntity::whereIn('id', $attrId)->get('id', 'type', 'name');
        $properties = $product_properties = [];
        foreach ($attr_list as $item) {
            $group = $data[$item['id']];
            $properties_label[] = sprintf('[%s]:%s', $item['name'], implode(',', $group['label']));
            if ($item->type == 2) {
                $properties = array_merge($properties, $group['items']);
                $properties_price += $group['price'];
                continue;
            }
            $product_properties = array_merge($product_properties, $group['items']);
        }
        return [$product_properties, $properties, $properties_price, implode(';', $properties_label)];
    }

    public static function getProperties(int $group, int $goods): array {
        if ($group < 1) {
            return [];
        }
        $attr_list = AttributeModel::where('group_id', $group)
            ->where('type', '>', 0)->orderBy('position asc')->orderBy('type asc')
            ->get('id', 'name', 'type');
        if (empty($attr_list)) {
            return [];
        }
        return array_filter(Relation::create($attr_list, [
            'attr_items' => [
                'query' => GoodsAttributeModel::where('goods_id', $goods),
                'link' => ['id', 'attribute_id'],
            ]
        ]), function ($item) {
            return empty($item->attr_items);
        });
    }

    public static function getStaticProperties(int $group, int $goods) {
        if ($group < 1) {
            return [];
        }
        $attr_list = AttributeUniqueModel::where('group_id', $group)
            ->where('type', 0)->orderBy('position asc')->orderBy('type asc')
            ->get('id', 'name', 'property_group');
        if (empty($attr_list)) {
            return [];
        }
        $items = Relation::create($attr_list, [
            'attr_item' => [
                'query' => GoodsAttributeModel::where('goods_id', $goods),
                'link' => ['id', 'attribute_id'],
                'type' => Relation::TYPE_ONE
            ]
        ]);
        $data = [];
        foreach ($items as $item) {
            if (empty($item['attr_item'])) {
                continue;
            }
            $groupName = trim($item['property_group']);
            if (!isset($data[$groupName])) {
                $data[$groupName] = [
                    'name' => $groupName,
                    'items' => []
                ];
            }
            $data[$groupName]['items'][] = $item;
        }
        return array_values($data);
    }

    /**
     * 一次性获取属性及静态属性
     * @param int $group
     * @param int $goods
     * @return array{properties: array, static_properties: array}
     * @throws \Exception
     */
    public static function batchProperties(int $group, int $goods): array {
        $properties = [];
        $static_properties = [];
        if ($group < 1) {
            return compact('properties', 'static_properties');
        }
        $attr_list = AttributeEntity::where('group_id', $group)
            ->orderBy('position asc')->orderBy('type asc')
            ->get('id', 'name', 'property_group', 'type');
        if (empty($attr_list)) {
            return compact('properties', 'static_properties');
        }
        $attrId = [];
        foreach ($attr_list as $item) {
            $attrId[] = $item['id'];
            if ($item['type'] > 0) {
                $properties[$item['id']] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'type' => intval($item['type']),
                    'attr_items' => []
                ];
                continue;
            }
            $groupName = trim($item['property_group']);
            if (!isset($static_properties[$groupName])) {
                $static_properties[$groupName] = [
                    'name' => $groupName,
                    'items' => []
                ];
            }
            $static_properties[$groupName]['items'][$item['id']] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'group' => $groupName,
                'attr_item' => null,
            ];
        }
        $items = GoodsAttributeModel::where('goods_id', $goods)
            ->whereIn('attribute_id', $attrId)->get();
        foreach ($items as $item) {
            $attrId = intval($item['attribute_id']);
            if (isset($properties[$attrId])) {
                $properties[$item['attribute_id']]['attr_items'][] = $item;
                continue;
            }
            foreach ($static_properties as $n => $group) {
                if (isset($group['items'][$attrId])) {
                    $static_properties[$n]['items'][$attrId]['attr_item'] = $item;
                }
            }
        }
        $items = [];
        foreach ($properties as $item) {
            if (!empty($item['attr_items'])) {
                $items[] = $item;
            }
        }
        $properties = $items;
        $items = [];
        foreach ($static_properties as $group) {
            $children = [];
            foreach ($group['items'] as $item) {
                if (!empty($item['attr_item'])) {
                    $children[] = $item;
                }
            }
            if (!empty($children)) {
                $group['items'] = $children;
                $items[] = $group;
            }
        }
        $static_properties = $items;
        return compact('properties', 'static_properties');
    }
}