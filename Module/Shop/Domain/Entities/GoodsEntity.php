<?php
namespace Module\Shop\Domain\Entities;

use Domain\Entities\Entity;
use Module\Shop\Domain\Models\ProductModel;
use Zodream\Database\Model\Query;

class GoodsEntity extends Entity {
    const STATUS_SALE = 10;
    const STATUS_OFF = 0;

    protected array $append = ['shop'];

    public static function tableName(): string {
        return 'shop_goods';
    }

    public function rules(): array {
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
            'type' => 'int:0,99',
            'admin_note' => '',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels(): array {
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
            'type' => '商品类型',
            'admin_note' => '管理员备注',
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

    public function products() {
        return $this->hasMany(ProductModel::class, 'goods_id', 'id');
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
}