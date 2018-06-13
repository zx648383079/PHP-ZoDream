<?php
namespace Module\Shop\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;
use Zodream\Html\Page;

/**
 * Class GoodsModel
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property integer $cat_id
 * @property integer $brand_id
 * @property string $name
 * @property string $sign
 * @property string $keywords
 * @property string $thumb
 * @property string $description
 * @property string $brief
 * @property string $content
 * @property float $price
 * @property float $market_price
 * @property integer $stock
 * @property integer $is_show
 * @property integer $is_best
 * @property integer $is_hot
 * @property integer $is_new
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class GoodsModel extends Model {
    public static function tableName() {
        return 'shop_goods';
    }

    protected function rules() {
        return [
            'cat_id' => 'required|int',
            'brand_id' => 'required|int',
            'name' => 'required|string:0,100',
            'sign' => 'required|string:0,100',
            'keywords' => 'string:0,200',
            'thumb' => 'string:0,200',
            'description' => 'string:0,200',
            'brief' => 'string:0,200',
            'content' => 'required',
            'price' => '',
            'market_price' => '',
            'stock' => 'int',
            'is_show' => 'int:0,9',
            'is_best' => 'int:0,9',
            'is_hot' => 'int:0,9',
            'is_new' => 'int:0,9',
            'deleted_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'cat_id' => 'Cat Id',
            'brand_id' => 'Brand Id',
            'name' => 'Name',
            'sign' => 'Sign',
            'keywords' => 'Keywords',
            'thumb' => 'Thumb',
            'description' => 'Description',
            'brief' => 'Brief',
            'content' => 'Content',
            'price' => 'Price',
            'market_price' => 'Market Price',
            'stock' => 'Stock',
            'is_show' => 'Is Show',
            'is_best' => 'Is Best',
            'is_hot' => 'Is Hot',
            'is_new' => 'Is New',
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

    /**
     * @return Page
     */
    public function getComments() {
        return new Page();
    }

    /**
     * @return array
     */
    public function getProperties() {
        return $this->hasMany(GoodsPropertyModel::class, 'goods_id', 'id');
    }

    /**
     * @return GoodsImageModel[]
     */
    public function getImages() {
        return $this->hasMany(GoodsImageModel::class, 'goods_id', 'id');
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
}