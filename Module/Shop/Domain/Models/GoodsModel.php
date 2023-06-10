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
 * @property integer $type
 * @property string $admin_note
 * @property integer $deleted_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class GoodsModel extends GoodsEntity {


    public function gallery() {
        return $this->hasMany(GoodsGalleryModel::class, 'goods_id', 'id');
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

    public function getPictureAttribute() {
        $thumb = $this->getAttributeSource('picture');
        if (empty($thumb)) {
            return '';
        }
        return url()->asset($thumb);
    }

    public function getIsCollectAttribute() {
        if (auth()->guest()) {
            return false;
        }
        return CollectModel::where('user_id', auth()->id())->where('goods_id', $this->id)->count() > 0;
    }

    public function getCommentCountAttribute() {
        return CommentModel::where('item_type', 0)->where('item_id', $this->id)->count();
    }

}