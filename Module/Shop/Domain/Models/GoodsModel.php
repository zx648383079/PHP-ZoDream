<?php
namespace Module\Shop\Domain\Models;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

use Module\Shop\Domain\Entities\GoodsEntity;
use Zodream\Database\Model\Query;
use Zodream\Database\Relation;
use Zodream\Html\Page;

/**
 * Class GoodsModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $cat_id
 * @property integer $brand_id
 * @property string $name
 * @property string $series_number
 * @property string $keywords
 * @property string $thumb
 * @property string $picture
 * @property string $description
 * @property string $brief
 * @property string $content
 * @property float $price
 * @property float $market_price
 * @property integer $stock
 * @property integer $attribute_group_id
 * @property float $weight
 * @property integer $shipping_id
 * @property integer $is_best
 * @property integer $is_hot
 * @property integer $is_new
 * @property integer $status
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property AttributeModel[] $static_properties
 * @property AttributeModel[] $properties
 */
class GoodsModel extends GoodsEntity {



    /**
     * @return array
     */
    public function getTags() {
        return [];
    }

    public function getPrice($amount) {
        return $this->price * $amount;
    }

    public function getIsCollectAttribute() {
        if (auth()->guest()) {
            return false;
        }
        return CollectModel::where('user_id', auth()->id())->where('goods_id', $this->id)->count() > 0;
    }

    public function canBuy(int $amount = 1): bool {
        return true;
    }

    /**
     * 获取最终的单价
     * @param int $amount
     * @param null $properties
     * @return float|int
     */
    public function final_price(int $amount = 1, $properties = null): float {
        if (empty($properties)) {
            return $this->price;
        }
        $box = AttributeModel::getProductAndPriceWithProperties($properties, $this->id);
        if (empty($box['product'])) {
            return $this->price + $box['properties_price'];
        }
        return $box['product']->price + $box['properties_price'];
    }

    public function getPropertiesAttribute() {
        if ($this->attribute_group_id < 1) {
            return [];
        }
        $attr_list = AttributeModel::where('group_id', $this->attribute_group_id)
            ->where('type', '>', 0)->orderBy('position asc')->orderBy('type asc')
            ->get('id', 'name', 'type');
        if (empty($attr_list)) {
            return [];
        }
        return array_filter(Relation::create($attr_list, [
            'attr_items' => [
                'query' => GoodsAttributeModel::where('goods_id', $this->id),
                'link' => ['id', 'attribute_id'],
            ]
        ]), function ($item) {
            return empty($item->attr_items);
        });
    }

    public function getStaticPropertiesAttribute() {
        if ($this->attribute_group_id < 1) {
            return [];
        }
        $attr_list = AttributeModel::where('group_id', $this->attribute_group_id)
            ->where('type', 0)->orderBy('position asc')->orderBy('type asc')->get('id', 'name');
        if (empty($attr_list)) {
            return [];
        }
        return array_filter(Relation::create($attr_list, [
            'attr_item' => [
                'query' => GoodsAttributeModel::where('goods_id', $this->id),
                'link' => ['id', 'attribute_id'],
                'type' => Relation::TYPE_ONE
            ]
        ]), function ($item) {
            return empty($item->attr_item);
        });
    }

}