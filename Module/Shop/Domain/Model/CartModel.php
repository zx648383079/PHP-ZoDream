<?php
namespace Module\Shop\Domain\Model;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
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
class CartModel extends BaseGoodsModel {
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
        return static::findAll(['user_id' => Factory::user()->getId()]);
    }

    /**
     * 根据ID 获取
     * @param array|string $args
     * @return static[]
     */
    public static function getSomeGoods($args) {
        return static::findAll([
            'user_id' => Factory::user()->getId(),
            'goods_id' => [
                'in',
                $args
            ]
        ]);
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
        $session_ip = $ip . "_" . Factory::session()->id();
        $time = time() + (3600 * 24 * 365);
        Factory::response()->header->setCookie('session_id_ip', $session_ip, $time, "/");
        return $session_ip;
    }
}