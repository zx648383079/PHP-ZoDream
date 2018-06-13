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
 * @package Domain\Model\Shopping
 * @property integer $goods_id
 * @property integer $user_id
 * @property integer $session_id
 * @property integer $activity_id //活动ID
 * @property string $activity_type //活动类型
 */
class CartModel extends Model {
    public static function tableName() {
        return 'shop_cart';
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

    }
    
    public static function fromGoods(GoodsModel $goods) {
        return new static();
    }
}