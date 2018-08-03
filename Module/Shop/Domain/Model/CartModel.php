<?php
namespace Module\Shop\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

use Zodream\Infrastructure\Cookie;

use Zodream\Service\Factory;

/**
 * Class CartModel
 * @property integer $id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $number
 * @property float $price
 * @property GoodsModel $goods
 */
class CartModel extends Model {
    public static function tableName() {
        return 'shop_cart';
    }

    protected function rules() {
        return [
            'user_id' => 'required|int',
            'goods_id' => 'required|int',
            'number' => 'int',
            'price' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'goods_id' => 'Goods Id',
            'number' => 'Number',
            'price' => 'Price',
        ];
    }

    public function getTotal() {
        return bcmul($this->number, $this->price);
    }

    public function goods() {
        return $this->hasOne(GoodsModel::class, 'id', 'goods_id');
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

    public function save() {
        if ($this->number <= 0) {
            return $this->delete();
        }
        return parent::save();
    }

    /**
     * 获取session_ip
     * @return string
     */
    public static function getSessionIp() {
        $ip = app('request')->ip();
        $session_ip = app('request')->cookie('session_id_ip');
        if (!empty($session_ip)) {
            return $session_ip;
        }
        $session_ip = $ip . '_' . Factory::session()->id();
        Cookie::forever('session_id_ip', $session_ip, '/');
        return $session_ip;
    }

    public static function addGoods(GoodsModel $goods, $amount = 1) {
        return static::create([
            'user_id' => auth()->id(),
            'goods_id' => $goods->id,
            'number' => $amount,
            'price' => $goods->price
        ]);
    }
    
    public static function fromGoods(GoodsModel $goods) {
        return new static();
    }
}