<?php
namespace Module\Shop\Domain\Models;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

use Module\Shop\Domain\Cart\ICartItem;
use Module\Shop\Domain\Cart\Item;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Zodream\Infrastructure\Cookie;

use Zodream\Service\Factory;

/**
 * Class CartModel
 * @property integer $id
 * @property integer $type
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $amount
 * @property float $price
 * @property float $total
 * @property GoodsModel $goods
 * @property ProductModel $product
 * @property ActivityModel $activity
 */
class CartModel extends Model implements ICartItem {

    use Item;

    protected $append = ['goods'];

    public static function tableName() {
        return 'shop_cart';
    }

    protected function rules() {
        return [
            'type' => 'int:0,9',
            'user_id' => 'required|int',
            'goods_id' => 'required|int',
            'amount' => 'int',
            'price' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => 'Type',
            'user_id' => 'User Id',
            'goods_id' => 'Goods Id',
            'amount' => '数量',
            'price' => 'Price',
        ];
    }

    public function goods() {
        return $this->hasOne(GoodsSimpleModel::class, 'id', 'goods_id');
    }

    public function getTotalAttribute() {
        return $this->amount * $this->price;
    }

    public function getSavingAttribute() {
        return $this->goods->market_price - $this->price;
    }

    /**
     * 获取所有的商品
     * @return static[]
     */
    public static function getAllGoods() {
        return static::with('goods')->belongOwn()->all();
    }

    /**
     * 根据ID 获取
     * @param array|string $args
     * @return static[]
     */
    public static function getSomeGoods($args) {
        return static::with('goods')->belongOwn()->whereIn('id', $args)->all();
    }

    public static function getSomeByGoods($args) {
        return static::with('goods')->belongOwn()->whereIn('goods_id', $args)->all();
    }

    public function scopeBelongOwn($query) {
//        if (auth()->guest()) {
//            return $query->where('session_id', static::getSessionIp());
//        }
        return $query->where(function ($query) {
            $query->where('user_id', auth()->id())
//                ->orWhere('session_id', static::getSessionIp())
            ;
        });
    }

    public function updateAmount(int $amount = 1) {
        if ($amount < 1) {
            return $this->delete();
        }
        $this->amount = $amount;
        $this->price = $this->goods->final_price($amount);
        return $this->save();
    }

    public function save() {
        if ($this->amount <= 0) {
            return $this->delete();
        }
        return parent::save();
    }


    public static function addGoods(GoodsModel $goods, $amount = 1) {
        $model = self::fromGoods($goods, $amount);
        return $model->save() ? $model : false;
    }
    
    public static function fromGoods(GoodsModel $goods, $amount = 1) {
        $model = new static([
            'user_id' => auth()->id(),
            'goods_id' => $goods->id,
            'amount' => $amount,
            'price' => $goods->final_price($amount)
        ]);
        $model->setRelation('goods', $goods);
        return $model;
    }

    public static function clearAll() {
        return self::query()->delete();
    }

    public static function deleteAll() {
        self::where('user_id', auth()->id)
            ->delete();
    }

    /**
     * 根据id删除购物车商品
     * @param $ids
     * @return mixed|void
     * @throws \Exception
     */
    public static function deleteById($ids) {
        if (empty($ids)) {
            return;
        }
        self::where('user_id', auth()->id)
            ->whereIn('id', is_array($ids) ? $ids : [intval($ids)])
            ->delete();
    }

    /**
     * 根据商品id删除购物车商品
     * @param $ids
     * @throws \Exception
     */
    public static function deleteByGoods($ids) {
        if (empty($ids)) {
            return;
        }
        self::where('user_id', auth()->id)
            ->whereIn('goods_id', is_array($ids) ? $ids : [intval($ids)])
            ->delete();
    }

}