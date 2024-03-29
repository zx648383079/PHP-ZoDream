<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;
use Module\Shop\Domain\Cart\ICartItem;
use Module\Shop\Domain\Repositories\AttributeRepository;
use Module\Shop\Domain\Repositories\CartRepository;


/**
 * Class OrderGoodsModel
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property integer $product_id
 * @property integer $user_id
 * @property string $name
 * @property string $series_number
 * @property string $thumb
 * @property integer $amount
 * @property float $price
 * @property integer $refund_id
 * @property integer $status
 * @property integer $after_sale_status
 * @property integer $comment_id
 * @property string $type_remark
 */
class OrderGoodsModel extends Model {

    protected array $append = ['status_label'];

    public static function tableName(): string {
        return 'shop_order_goods';
    }

    public function rules(): array {
        return [
            'order_id' => 'required|int',
            'goods_id' => 'required|int',
            'product_id' => 'int',
            'user_id' => 'required|int',
            'name' => 'required|string:0,100',
            'series_number' => 'required|string:0,100',
            'thumb' => 'string:0,200',
            'amount' => 'int',
            'price' => '',
            'refund_id' => 'int',
            'status' => 'int',
            'after_sale_status' => 'int',
            'comment_id' => 'int',
            'type_remark' => '',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'order_id' => 'Order Id',
            'goods_id' => 'Goods Id',
            'product_id' => 'Product Id',
            'user_id' => 'User Id',
            'name' => 'Name',
            'series_number' => 'Series Number',
            'thumb' => 'Thumb',
            'amount' => 'Number',
            'price' => 'Price',
            'refund_id' => 'Refund Id',
            'status' => 'Status',
            'after_sale_status' => 'After Sale Status',
            'comment_id' => 'Comment Id',
        ];
    }

    public function goods() {
        return $this->hasOne(GoodsSimpleModel::class, 'id', 'goods_id');
    }


    public function scopeAuth($query) {
        return $query->where('user_id', auth()->id());
    }


    /**
     * @return float
     */
    public function getTotalAttribute() {
        return $this->price * $this->amount;
    }

    public function getStatusLabelAttribute() {
        return OrderModel::$status_list[$this->status];
    }

    /**
     * 一步购买
     * @param OrderModel $order
     * @param GoodsModel $goods
     * @param integer $amount
     * @return static
     */
    public static function addGoods(OrderModel $order, GoodsModel $goods, $amount) {
        $model = new static();
        $model->goods_id = $goods->id;
        $model->status = $order->status;
        $model->user_id = $order->user_id;
        $model->order_id = $order->id;
        $model->name = $goods->name;
        $model->thumb = $goods->thumb;
        $model->series_number = $goods->series_number;
        $model->price = $goods->price;
        $model->amount = $amount;
        $model->save();
        return $model;
    }

    /**
     * 从购物车中转入
     * @param OrderModel $order
     * @param ICartItem $cart
     * @param integer $amount
     * @return static
     * @throws \Exception
     */
    public static function addCartGoods(OrderModel $order, ICartItem $cart, int $amount = 0) {
        if (empty($amount)) {
            $amount = $cart->amount();
        }
        $model = new static();
        $model->status = $order->status;
        $model->user_id = $order->user_id;
        $model->goods_id = $cart->goodsId();
        $model->order_id = $order->id;
        $goods = CartRepository::getGoods($cart->goodsId());
        $model->name = $goods->name;
        $model->series_number = $goods->series_number;
        $model->thumb = $goods->thumb;
        $model->price = $cart->price();
        $model->amount = $amount;
        $model->product_id = $cart->productId();
        $box = AttributeRepository::getProductAndPriceWithProperties($cart->properties(), $cart->goodsId());
        $model->type_remark = $box['properties_label'];
        $model->save();
        return $model;
    }
}