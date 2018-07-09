<?php
namespace Module\Shop\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Cookie;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Factory;

/**
 * Class CartModel
 * @property integer $id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $number
 * @property float $price
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
        if (Auth::guest()) {
            return $query->where('session_id', static::getSessionIp());
        }
        return $query->where(function ($query) {
            $query->where('user_id', Auth::id())
                ->orWhere('session_id', static::getSessionIp());
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
        $ip = Request::ip();
        $session_ip = Request::cookie('session_id_ip');
        if (!empty($session_ip)) {
            return $session_ip;
        }
        $session_ip = $ip . '_' . Factory::session()->id();
        Cookie::forever('session_id_ip', $session_ip, '/');
        return $session_ip;
    }

    public static function addGoods(GoodsModel $goods, $amount) {
        return static::create([
            'user_id' => Auth::id(),
            'goods_id' => $goods->id,
            'amount' => $amount,
            'price' => $goods->price
        ]);
    }
    
    public static function fromGoods(GoodsModel $goods) {
        return new static();
    }
}