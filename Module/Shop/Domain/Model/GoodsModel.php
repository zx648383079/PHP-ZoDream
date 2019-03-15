<?php
namespace Module\Shop\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

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
class GoodsModel extends Model {

    const STATUS_SALE = 10;
    const STATUS_OFF = 0;

    const SORT_NONE = 0;
    const SORT_PRICE = 1;
    const SORT_NAME = 2;
    const SORT_SALE = 3;
    const SORT_ID = 4;
    const SORT_HOT = 5;


    const THUMB_MODE = ['id', 'name', 'series_number', 'thumb', 'price', 'market_price'];

    public static function tableName() {
        return 'shop_goods';
    }

    protected function rules() {
        return [
            'cat_id' => 'required|int',
            'brand_id' => 'required|int',
            'name' => 'required|string:0,100',
            'series_number' => 'required|string:0,100',
            'keywords' => 'string:0,200',
            'thumb' => 'string:0,200',
            'picture' => 'required|string:0,200',
            'description' => 'string:0,200',
            'brief' => 'string:0,200',
            'content' => 'required',
            'price' => '',
            'market_price' => '',
            'stock' => 'int',
            'attribute_group_id' => 'int',
            'weight' => '',
            'shipping_id' => 'int',
            'sales' => 'int',
            'is_best' => 'int:0,9',
            'is_hot' => 'int:0,9',
            'is_new' => 'int:0,9',
            'status' => 'int:0,99',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'cat_id' => '分类',
            'brand_id' => '品牌',
            'name' => '商品名',
            'series_number' => '货号',
            'keywords' => '关键字',
            'thumb' => '缩略图',
            'picture' => '主图',
            'description' => '说明',
            'brief' => '简介',
            'content' => '内容',
            'price' => '价格',
            'attribute_group_id' => '类型',
            'market_price' => '市场价',
            'weight' => '重量',
            'shipping_id' => '配送方式',
            'stock' => '库存',
            'is_best' => '精品',
            'is_hot' => '热门',
            'is_new' => '最新',
            'status' => '状态',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public function category() {
        return $this->hasOne(CategoryModel::class, 'id', 'cat_id');
    }

    public function brand() {
        return $this->hasOne(BrandModel::class, 'id', 'brand_id');
    }

    public function getThumbAttribute() {
        $thumb = $this->getAttributeSource('thumb');
        if (empty($thumb)) {
            return '';
        }
        return url()->asset($thumb);
    }

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

    /**
     * @param $sort
     * @param $order
     * @return Query
     */
    public static function sortBy($sort, $order) {
        if (empty($sort)) {
            return static::query();
        }
        $get_order = function ($order, $def = 'desc') {
            return is_null($order) ? $def : $order > 0 ? 'desc' : 'asc';
        };
        if ($sort == self::SORT_NAME) {
            return static::orderBy('name', $get_order($order, 'asc'));
        }
        if ($sort == self::SORT_PRICE) {
            return static::orderBy('price', $get_order($order, 'asc'));
        }
        if ($sort == self::SORT_ID) {
            return static::orderBy('id', $get_order($order, 'desc'));
        }
        return static::query();
    }
}