<?php
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;

class GoodsEntity extends Entity {
    const STATUS_SALE = 10;
    const STATUS_OFF = 0;

    const SORT_NONE = 0;
    const SORT_PRICE = 1;
    const SORT_NAME = 2;
    const SORT_SALE = 3;
    const SORT_ID = 4;
    const SORT_HOT = 5;

    protected $append = ['shop'];

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
        return $this->hasOne(CategoryEntity::class, 'id', 'cat_id');
    }

    public function brand() {
        return $this->hasOne(BrandEntity::class, 'id', 'brand_id');
    }

    public function getShopAttribute() {
        return config('app.name');
    }

    public function getThumbAttribute() {
        $thumb = $this->getAttributeSource('thumb');
        if (empty($thumb)) {
            return '';
        }
        return url()->asset($thumb);
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
            return is_null($order) ? $def : ($order > 0 ? 'desc' : 'asc');
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